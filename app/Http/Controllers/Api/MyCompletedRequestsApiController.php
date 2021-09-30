<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Server;
use App\User;
use Illuminate\Http\JsonResponse;

class MyCompletedRequestsApiController extends Controller
{
    public function show(int $id) : JsonResponse
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $server = session()->get('server');

        return response()->json([
            'count' => $user->requestCount(),
            'avg_time' => $user->getAverageTimePerDuty($server),
            'total_time' => $user->getTotalTimeSpentServing($server),
        ]);
    }
}
