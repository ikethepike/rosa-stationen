<?php

namespace Rosa\Http\Controllers\Api;

use Storage;
use Rosa\User;
use Illuminate\Http\Request;
use Rosa\Http\Controllers\Controller;
use Rosa\Http\Requests\User\RegisterRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterRequest $request)
    {
        return User::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user       = User::findOrFail($id);
        $user->rank = $user->position();

        return $user->load('attendance');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return (int) User::findOrFail($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::findOrFail($id)->destroy();
    }

    public function setAvatar(Request $request)
    {
        $url = Storage::putFile('avatars', $request->file('avatar'));

        if ($url) {
            $user         = $request->user();
            $user->avatar = Storage::url($url);
            $user->save();

            return $user->load('attendance.lessons');
        }

        return abort(500, 'S3 upload issue');
    }

    /**
     * Returns all teachers.
     *
     * @return collection
     */
    public function teachers()
    {
        return User::where('staff', true)->get();
    }
}
