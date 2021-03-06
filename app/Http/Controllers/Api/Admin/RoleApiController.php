<?php

namespace App\Http\Controllers\Api\Admin;

use App\AllowedRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateRoleRequest;
use App\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RoleApiController extends Controller
{
    public function update(UpdateRoleRequest $request, int $serverId) : JsonResponse
    {
        $role = AllowedRole::where('server_id', $serverId)
            ->where('role_id', $request->input('role_id'))
            ->first();

        if (!$role) {
            $role = new AllowedRole();
            $role->role_id = $request->input('role_id');
            $role->role_name = $request->input('role_name');
            $role->server()->associate($serverId);
            $role->save();

            $code = Response::HTTP_CREATED;
        } else {
            $role->delete();
            $role = [];
            $code = Response::HTTP_NO_CONTENT;
        }

        return response()->json($role)->setStatusCode($code);
    }
}
