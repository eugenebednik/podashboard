<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;

class MyCompletedRequestsApiController extends Controller
{
    public function show(int $id)
    {
        $user = User::findOrFail($id);
        return response()->json(['count' => $user->requestCount()]);
    }
}
