<?php

namespace App\Http\Controllers;

use App\BuffRequest;
use App\Services\DiscordWebhookService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    const PER_PAGE = 20;

    protected $discordService;

    /**
     * Create a new controller instance.
     *
     * @param DiscordWebhookService $discordWebhookService
     *
     * @return void
     */
    public function __construct(DiscordWebhookService $discordWebhookService)
    {
        $this->middleware(['auth', 'active']);
        $this->discordService = $discordWebhookService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $outstandingBuffRequests = BuffRequest::where('outstanding', true)->paginate(self::PER_PAGE);
        $fulfilledBuffRequests = BuffRequest::where('outstanding', false)->paginate(self::PER_PAGE);

        return view('dashboard', compact('outstandingBuffRequests', 'fulfilledBuffRequests'));
    }

    /**
     * Fulfill a buff request.
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function fulfill(Request $request, int $id)
    {
        /** @var BuffRequest $buffRequest */
        $buffRequest = BuffRequest::find($id);

        if ($buffRequest) {
            $buffRequest->outstanding = false;
            $buffRequest->handledBy()->associate(Auth::user());
            $buffRequest->save();

            // Reply to the user via Discord
            try {
                $this->discordService->handle(
                    $buffRequest->discord_snowflake,
                    __("your buff request for {$buffRequest->requestType->name} has been fulfilled.")
                );
            } catch (GuzzleException $e) {
                Log::error('Unable to fulfill Discord request.', $e->getTrace());
            }
        }

        return redirect('/dashboard');
    }
}
