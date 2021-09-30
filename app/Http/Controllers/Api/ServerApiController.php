<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateServerRequest;
use App\Http\Requests\Api\UpdateServerRequest;
use App\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ServerApiController extends Controller
{
    public function show(int $serverId) : JsonResponse
    {
        $server = Server::findOrFail($serverId)->load('onDuty');
        return response()->json($server)->setStatusCode(Response::HTTP_OK);
    }

    public function store(CreateServerRequest $request) : JsonResponse
    {
        $snowflake = $request->input('snowflake');
        $server = Server::where('snowflake', $snowflake)->first();

        if (!$server) {
            $server = Server::create([
                'snowflake' => $snowflake,
                'name' => Str::slug($request->input('name')),
                'is_active' => true,
                'webhook_id' => $request->input('webhook_id'),
                'webhook_token' => $request->input('webhook_token'),
            ]);

            $code = Response::HTTP_CREATED;
        } else {
            $code = Response::HTTP_CONFLICT;
        }

        return response()->json($server)->setStatusCode($code);
    }

    public function update(UpdateServerRequest $request, int $id) : JsonResponse
    {
        $server = Server::findOrFail($id);

        $parts = Str::replaceFirst(
            config('services.discord.api_base_url') . 'webhooks/', '', $request->input('webhook_url')
        );

        list($appId, $appToken) = explode('/', $parts);

        $server->webhook_id = $appId;
        $server->webhook_token = $appToken;
        $server->save();

        return response()->json($server)->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
