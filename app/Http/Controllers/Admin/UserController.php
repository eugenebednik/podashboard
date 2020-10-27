<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Server;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $out = [];
        $server = Server::findOrFail($request->session()->get('server_id'));
        $users = User::where('server_id', $server->id)->get();

        foreach ($users as $user) {
            $out[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'discord_id' => $user->discord_id,
                'count' => $user->requestCount(),
            ];
        }

        return view('admin.users')->with(['data' => $out, 'server' => $server]);
    }
}
