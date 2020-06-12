<?php

namespace App\Http\Controllers;

use App\Alliance;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $token = config('api.token');
        $alliances = Alliance::all();
        $usersServed = [];
        $allUsers = User::all();

        foreach ($allUsers as $user) {
            $usersServed[$user->id] = $user->requestCount();
        }

        return view('admin.users.index', compact('token', 'alliances', 'usersServed'));
    }
}
