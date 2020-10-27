<?php

namespace App\Http\Controllers;

use App\BuffRequest;
use App\Server;
use App\Services\DiscordService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

class DashboardController extends Controller
{
    use WithPagination;

    const PER_PAGE = 15;

    /**
     * @var DiscordService
     */
    protected $discordService;

    public function __construct(DiscordService $discordService)
    {
        $this->discordService = $discordService;
    }

    public function index(Request $request)
    {
        $server = Server::findOrFail($request->session()->get('server_id'));

        $outstandingBuffRequests = BuffRequest::where('server_id', $server->id)
            ->whereNull('handled_by')
            ->where('outstanding', true)
            ->paginate(self::PER_PAGE);
        $fulfilledBuffRequests = BuffRequest::where('server_id', $server->id)
            ->whereNotNull('handled_by')
            ->where('outstanding', true)
            ->get();

        foreach ($fulfilledBuffRequests as $buffRequest) {
            if ($buffRequest->updated_at->lt(Carbon::now()->subMinutes(config('buff-requests.minutes-to-disappear')))) {
                BuffRequest::where('id', $buffRequest->id)->update(['outstanding' => false]);
            }
        }

        $fulfilledBuffRequests = BuffRequest::where('server_id', $server->id)
            ->whereNotNull('handled_by')
            ->where('outstanding', true)
            ->paginate(self::PER_PAGE);

        $countMyRequests = BuffRequest::where('handled_by', Auth::user()->id)->count();

        return view('dashboard', compact(
                'outstandingBuffRequests',
                'fulfilledBuffRequests',
                'countMyRequests')
        )->with(['server' => $server]);
    }

    public function fulfill(Request $request, int $id)
    {
        /** @var BuffRequest $buffRequest */
        $buffRequest = BuffRequest::find($id);

        if ($buffRequest) {
            $buffRequest->handledBy()->associate(Auth::user());
            $buffRequest->save();

            // Reply to the user via Discord
            try {
                $this->discordService->respondViaWebhook(
                    $buffRequest->discord_snowflake,
                    __("your buff request for {$buffRequest->requestType->name} has been fulfilled.")
                );
            } catch (GuzzleException $e) {
                Log::error('Unable to fulfill Discord request.', $e->getTrace());
            }
        }

        return redirect()->route('dashboard');
    }
}
