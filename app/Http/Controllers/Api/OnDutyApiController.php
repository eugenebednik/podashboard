<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateOnDutyRequest;
use App\OnDuty;
use App\Server;
use App\Services\DiscordService;
use App\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class OnDutyApiController extends Controller
{
    /**
     * @var DiscordService
     */
    protected $discordService;

    public function __construct(DiscordService $discordService)
    {
        $this->discordService = $discordService;
    }

    public function update(UpdateOnDutyRequest $request, int $serverId)
    {
        /** @var Server $server */
        $server = Server::findOrFail($serverId);

        /** @var User $user */
        $user = User::findOrFail($request->input('user_id'));

        if ($server->isUserOnDuty($user)) {
            $createdAt = $user->onDuty->created_at;
            $user->onDuty()->delete();
            $code = Response::HTTP_NO_CONTENT;
            $diff = $createdAt->longAbsoluteDiffForHumans(now());

            try {
                $message = "📣 PO is now offline. We thank <@{$user->discord_id}> for their service which lasted: `$diff`.";
                $this->discordService->sayViaWebhook($server, $message);
            } catch (GuzzleException $e) {
                Log::error('Unable to fulfill Discord request: ' . $e->getMessage(), $e->getTrace());
            }
        } else {
            $server->onDuty()->delete();

            $onDuty = new OnDuty();
            $onDuty->user()->associate($user);
            $onDuty->server()->associate($server);
            $onDuty->save();
            $code = Response::HTTP_CREATED;

            try {
                $message = "📣 Hear ye, hear ye! A PO has come online! Please greet your PO <@{$user->discord_id}>!";
                $this->discordService->sayViaWebhook($server, $message);
            } catch (GuzzleException $e) {
                Log::error('Unable to fulfill Discord request: ' . $e->getMessage(), $e->getTrace());
            }
        }

        $server->load('onDuty');

        return response()->json($server)->setStatusCode($code);
    }
}