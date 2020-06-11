<?php

namespace App\Http\Controllers;

class InactiveController extends Controller
{
    /**
     * Return the inactive view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('inactive');
    }
}
