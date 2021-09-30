<?php

namespace App\Http\Controllers\Api;

use App\BuffRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexBuffRequests;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BuffRequestApiController extends Controller
{
    public function index(IndexBuffRequests $request) : JsonResponse
    {
        $serverId = session()->get('server')->id;

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

    public function show(BuffRequest $buffRequest) : JsonResponse
    {
        return response()->json($buffRequest->load('server'))->setStatusCode(Response::HTTP_OK);
    }
}
