<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Server;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServerApiController extends Controller
{
    public function show(Request $request, Server $server)
    {
        return response()->json($server->load('allowedRoles'))->setStatusCode(Response::HTTP_OK);
    }
}
