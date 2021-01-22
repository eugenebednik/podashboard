<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateAdminRequest;
use App\Server;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserAdminApiController extends Controller
{
    public function show(Request $request, int $serverId)
    {
        return response()->json(Server::findOrFail($serverId)->load('administrators'))
            ->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateAdminRequest $request, int $serverId)
    {
        /** @var Server $server */
        $server = Server::findOrFail($serverId);
        $user = User::findOrFail($request->input('user_id'));

        if (!$server->administrators->contains($user)) {
            $server->administrators()->attach($user);
            $code = Response::HTTP_CREATED;
        } else {
            $server->administrators()->detach($user);
            $code = Response::HTTP_NO_CONTENT;
        }

        return response()->json($server->load('administrators'))->setStatusCode($code);
    }
}
