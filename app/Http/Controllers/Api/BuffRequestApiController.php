<?php

namespace App\Http\Controllers\Api;

use App\BuffRequest;
use App\Http\Controllers\Api\Mixins\GetServerMixin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBuffRequest;
use App\Http\Requests\Api\UpdateBuffRequest;
use App\RequestType;
use App\Server;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BuffRequestApiController extends Controller
{
    use GetServerMixin;

    public function index(Request $request)
    {
        $serverId = $request->query('server_id');

        $outstanding = BuffRequest::with(['server', 'requestType'])
            ->where('server_id', $serverId)
            ->whereNull('handled_by')
            ->where('outstanding', true)
            ->get();

        $fulfilled = BuffRequest::with(['server', 'requestType'])
            ->where('server_id', $serverId)
            ->whereNotNull('handled_by')
            ->where('outstanding', true)
            ->get();

        foreach ($fulfilled as $key => $buffRequest) {
            if ($buffRequest->updated_at->lt(Carbon::now()->subMinutes(config('buff-requests.minutes-to-disappear')))) {
                $fulfilled->forget($key);
                BuffRequest::where('id', $buffRequest->id)->update(['outstanding' => false]);
            }
        }

        return response()->json(['outstanding' => $outstanding, 'fulfilled' => $fulfilled])
            ->setStatusCode(Response::HTTP_OK);
    }

    public function show(BuffRequest $buffRequest)
    {
        return response()->json($buffRequest)->setStatusCode(Response::HTTP_OK);
    }

    public function store(CreateBuffRequest $request)
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

    public function update(UpdateBuffRequest $request, BuffRequest $buffRequest)
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
