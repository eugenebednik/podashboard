<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateServerRequest;
use App\Server;
use Illuminate\Http\Response;

class ServerApiController extends Controller
{
    public function store(CreateServerRequest $request)
    {
        $snowflake = $request->input('snowflake');
        $server = Server::where('snowflake', $snowflake)->first();

        if (!$server) {
            $server = Server::create([
               'snowflake' => $snowflake,
               'name' => $request->input('name'),
               'is_active' => true,
            ]);

            $code = Response::HTTP_CREATED;
        } else {
            $code = Response::HTTP_CONFLICT;
        }

        return response()->json($server)->setStatusCode($code);
    }
}
