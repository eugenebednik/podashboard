<?php

namespace App\Http\Controllers\Api;

use App\BuffRequest;
use App\Http\Controllers\Api\Mixins\GetServerMixin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBuffRequest;
use App\Http\Requests\Api\UpdateBuffRequest;
use App\RequestType;
use App\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BotRequestsApiController extends Controller
{
    use GetServerMixin;

    public function store(CreateBuffRequest $request) : JsonResponse
    {
        /** @var Server $server */
        $server = $this->getServer($request->input('server_snowflake'));

        // Check to see if anyone is "on duty"
        if (!$server->hasUserOnDuty()) {
            return response()->json(['errors' => __('No PO is currently on duty')])
                ->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
        }

        // Check to see if this user already has a request pending.
        $requestExists = BuffRequest::where('discord_snowflake', $request->input('discord_snowflake'))
            ->where('server_id', $server->id)
            ->where('outstanding', true)
            ->exists();

        if ($requestExists) {
            return response()->json(['errors' => __('Request already exists.')])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $buffRequest = new BuffRequest();
        $buffRequest->fill($request->all());
        $buffRequest->requestType()->associate(RequestType::findOrFail($request->input('request_type_id')));
        $buffRequest->server()->associate($server);
        $buffRequest->save();

        return response()->json($buffRequest)->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateBuffRequest $request, BuffRequest $buffRequest) : JsonResponse
    {
        $server = $this->getServer($request->input('server_snowflake'));

        $buffRequest->fill($request->all());
        $buffRequest->requestType()->associate(RequestType::findOrFail($request->input('request_type_id')));
        $buffRequest->server()->associate($server);
        $buffRequest->handledBy()->associate(Auth::user());
        $buffRequest->save();

        return response()->json($buffRequest)->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(BuffRequest $buffRequest)
    {
        /** @var BuffRequest $buffRequest */
        $buffRequest->delete();

        return response()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
