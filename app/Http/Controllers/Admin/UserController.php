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
        $users = $server->users;

        foreach ($users as $user) {
            /** @var User $user */
            $out[] = [
                'id' => $user->id,
                'name' => $user->name,
                'discord_id' => $user->discord_id,
                'count' => $user->requestCount(),
                'average_time_per_session' => $user->getAverageTimePerDuty($server) ?? 'n/a',
                'total_time_spent_serving' => $user->getTotalTimeSpentServing($server) ?? 'n/a',
            ];
        }

        return view('admin.users')->with(['users' => $out, 'server' => $server]);
    }
}
