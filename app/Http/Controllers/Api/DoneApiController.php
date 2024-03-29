<?php

namespace App\Http\Controllers\Api;

use App\BuffRequest;
use App\Http\Controllers\Api\Mixins\GetServerMixin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateDoneRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class DoneApiController extends Controller
{
    use GetServerMixin;

    public function store(CreateDoneRequest $request) : JsonResponse
    {
        $server = $this->getServer($request->input('server_snowflake'));

        // Check to see if anyone is "on duty"
        if (!$server->hasUserOnDuty()) {
            return response()->json(['errors' => __('No PO is currently on duty')])
                ->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
        }

        if (BuffRequest::where('discord_snowflake', $request->input('discord_snowflake'))
            ->where('server_id', $server->id)
            ->where('outstanding', true)
            ->count() > 0)
        {
            BuffRequest::where('discord_snowflake', $request->input('discord_snowflake'))
                ->where('server_id', $server->id)
                ->where('outstanding', true)
                ->update([
                    'outstanding' => false,
                ]);

            return response()->json(['success' => true])->setStatusCode(Response::HTTP_CREATED);
        }

        return response()->json(['success' => false])->setStatusCode(Response::HTTP_NOT_FOUND);
    }
}
