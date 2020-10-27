<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $server = Server::findOrFail($request->session()->get('server_id'));

        return view('dashboard')->with([
            'server' => $server,
            'userId' => Auth::user()->id,
            'token' => Auth::user()->api_token,
        ]);
    }
}
