<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $serverId = $request->session()->get('server_id');

        if (Auth::user() && $serverId) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function welcome()
    {
        return view('welcome');
    }

    public function inactive(Request $request)
    {
        return view('inactive');
    }

    public function serverRequired(Request $request)
    {
        return view('server-required');
    }
}
