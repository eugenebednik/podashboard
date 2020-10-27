<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user() && $request->session()->get('server_id')) {
            return redirect()->route('dashboard');
        }

        $serverSnowflake = $request->query('server_id');

        if (!$serverSnowflake) {
            return redirect()->route('server-required');
        }

        $server = Server::where('snowflake', $serverSnowflake)->first();

        if (!$server) {
            return redirect()->route('inactive');
        }

        $serverAdminCount = $server->administrators->count();

        $request->session()->put('server_id', $server->id);
        $request->session()->put('is_new_setup', $serverAdminCount === 0);

        return view('auth.login');
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
