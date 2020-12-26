<?php

namespace App\Http\Controllers\Api;

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
        $requestTypes = RequestType::with(['requests' => function ($query) use ($server) {
            return $query->where('outstanding', true)
                ->where('server_id', $server->id)
                ->whereNull('handled_by')
                ->orderBy('created_at', 'asc');
        }])->get();

        foreach ($requestTypes as $requestType) {
            $out[Str::snake($requestType->name)] = $requestType->requests->toArray();
        }

        return response()->json($out)->setStatusCode(Response::HTTP_OK);
    }
}
