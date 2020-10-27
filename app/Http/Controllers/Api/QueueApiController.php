<?php

namespace App\Http\Controllers\Api;

use App\BuffRequest;
use App\Http\Controllers\Controller;
use App\RequestType;
use App\Server;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class QueueApiController extends Controller
{
    public function show(string $serverSnowflake)
    {
        $out = [];
        $server = Server::where('snowflake', $serverSnowflake)->firstOrFail();
        $requestTypes = RequestType::all();

        foreach ($requestTypes as $requestType) {
            $out[Str::snake($requestType->name)] = BuffRequest::where('outstanding', true)
                ->where('server_id', $server->id)
                ->whereNull('handled_by')
                ->where('request_type_id', $requestType->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();
        }

        return response()->json($out)->setStatusCode(Response::HTTP_OK);
    }
}
