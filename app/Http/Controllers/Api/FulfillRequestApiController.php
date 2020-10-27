<?php

namespace App\Http\Controllers\Api;

use App\BuffRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FulfillRequest;
use App\Services\DiscordService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class FulfillRequestApiController extends Controller
{
    /**
     * @var DiscordService
     */
    protected $discordService;

    public function __construct(DiscordService $discordService)
    {
        $this->discordService = $discordService;
    }

    public function update(FulfillRequest $request, int $id)
    {
        $buffRequest = BuffRequest::findOrFail($id);
        $buffRequest->handledBy()->associate($request->input('user_id'));
        $buffRequest->save();

        // Reply to the user via Discord
        try {
            $this->discordService->respondViaWebhook(
                $buffRequest->server,
                $buffRequest->discord_snowflake,
                __("your buff request for {$buffRequest->requestType->name} has been fulfilled.")
            );
        } catch (GuzzleException $e) {
            Log::error('Unable to fulfill Discord request: ' . $e->getMessage(), $e->getTrace());
        }

        return response()->json($buffRequest)->setStatusCode(Response::HTTP_OK);
    }
}
