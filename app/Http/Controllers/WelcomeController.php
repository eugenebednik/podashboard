<?php

namespace App\Http\Controllers;

use App\Server;
use App\ServerAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $serverSnowflake = $request->query('server_id');

        if (!$serverSnowflake) {
            return view('server-required');
        }

        $server = Server::where('snowflake', $serverSnowflake)->first();

        if (!$server) {
            return view('inactive');
        }

        $serverAdminCount = ServerAdmin::where('server_id', $server->id)->count();

        Session::put('server_id', $server->id);
        Session::put('is_new_setup', $serverAdminCount === 0);

        return view('auth.login');
    }
}
