<?php

namespace App\Http\Controllers\Api\Admin;

use App\AllowedRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RoleApiController extends Controller
{
    public function update(UpdateRoleRequest $request, string $snowflake) : JsonResponse
    {
        $server = session()->get('server');

        $role = AllowedRole::where('server_id', $server->id)
            ->where('role_id', $snowflake)
            ->first();

        if (!$role) {
            $role = new AllowedRole();
            $role->role_id = $request->input('role_id');
            $role->role_name = $request->input('role_name');
            $role->server()->associate($server);
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
