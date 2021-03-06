<?php

namespace Rosa\Http\Controllers;

use Rosa\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $planning = new PlanningController();
        $term     = $planning->planning();

        return view('home')->with([
            'user'              => auth()->user(),
            'hasUsersToApprove' => $this->hasUsersToApprove(),
            'students'          => (object) [
                'registered' => $term ? $term->users : [],
            ],
            'highscore'         => User::orderBy('score', 'DESC')->where('staff', false)->first(),
            'week'              => $term && isset($term) ? $term->currentWeek()->load('attendance') : [],
        ]);
    }

    private function hasUsersToApprove()
    {
        return User::where('approved', false)->where('staff', true)->exists();
    }

    public function lessons()
    {
        return view('lessons')->with(['user' => auth()->user()]);
    }

    public function challenges()
    {
        return view('challenges')->with(['user' => auth()->user()]);
    }

    public function users()
    {
        $approve = User::where('staff', true)->where('approved', false)->get();

        return view('users')->with(['user' => auth()->user(), 'approve' => $approve]);
    }

    public function keys()
    {
        return view('keys')->with(['user' => auth()->user(), 'hasUsersToApprove' => true]);
    }
}
