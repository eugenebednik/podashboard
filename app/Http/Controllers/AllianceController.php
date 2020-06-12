<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class AllianceController extends Controller
{
    public function index()
    {
        $token = config('api.token');
        $alliancesServed = [];
        $allUsers = User::all();
        $thisUserAllianceId = Auth::user()->alliance->id;

        foreach ($allUsers as $user) {
            if (!isset($alliancesServed[$user->alliance->id])) {
                $alliancesServed[$user->alliance->id] = $user->requestCount();
            } else {
                $alliancesServed[$user->alliance->id] += $user->requestCount();
            }
        }

        return view('admin.alliances.index', compact(
            'token',
            'alliancesServed',
            'thisUserAllianceId')
        );
    }
}
