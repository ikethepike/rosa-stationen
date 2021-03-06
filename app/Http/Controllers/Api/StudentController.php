<?php

namespace Rosa\Http\Controllers\Api;

use Hash;
use Rosa\Term;
use Rosa\User;
use Rosa\Week;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Rosa\Http\Controllers\Controller;
use Rosa\Http\Requests\User\LoginRequest;
use Rosa\Http\Requests\User\RegisterRequest;

class StudentController extends Controller
{
    private $success  = 200;
    private $frontend = 'rosa';

    public function __construct()
    {
        $this->frontend = env('FRONTEND_NAME') || 'rosa';
    }

    /**
     * Log a user in and set a token.
     *
     * @param LoginRequest $request
     *
     * @return json
     */
    public function login(LoginRequest $request)
    {
        if (!User::where('email', $request->email)->exists()) {
            return abort(404, 'Incorrect email address');
        }

        if (auth()->attempt($request->all())) {
            $user       = auth()->user()->load('attendance.lessons');
            $user->rank = $user->position();

            return response()->json([
                    'user'  => $user,
                    'token' => auth()->user()->createToken($this->frontend)->accessToken,
                ],
                200
            );
        }

        return abort(401, 'Incorrect password');
    }

    /**
     * Register a student and send back token.
     *
     * @param RegisterRequest $request
     *
     * @return json
     */
    public function register(RegisterRequest $request)
    {
        $register             = $request->all();
        $register['password'] = Hash::make($request->password);
        $register['student']  = true;
        $user                 = User::create($register);
        $user->rank           = $user->position();

        return response()->json([
            'user'  => $user->load('attendance.lessons'),
            'token' => $user->createToken($this->frontend)->accessToken,
        ], 200);
    }

    /**
     * Check whether a given email exists.
     *
     * @param Request $request
     *
     * @return int
     */
    public function exists(Request $request)
    {
        return User::where('email', $request->email)->first();
    }

    public function markAttendance(Request $request)
    {
        $date       = new Carbon();
        $user       = $request->user()->load('attendance.lessons');
        $user->rank = $user->position();

        if ($user->attendedWeek) {
            return $user;
        }

        $term = Term::latest()->first();
        if (!$term->users()->where('users.id', $user->id)->exists()) {
            $term->users()->attach($user);
        }

        // Update attendance
        $user->attendance()->attach(Week::current());

        $user->score += 40;
        $user->save();

        return $user;
    }
}
