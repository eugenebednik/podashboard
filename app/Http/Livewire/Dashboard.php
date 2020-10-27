<?php

namespace App\Http\Livewire;

use App\BuffRequest;
use App\Services\DiscordService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    const PER_PAGE = 5;

    /**
     * @var DiscordService
     */
    public $discordService;
    public $serverId;

    protected $paginationTheme = 'bootstrap';

    public function mount(DiscordService $discordService)
    {
        $this->discordService = $discordService;
        $this->serverId = Auth::user()->server->id;
    }

    public function render()
    {
        $outstandingBuffRequests = BuffRequest::where('server_id', $serverId)
            ->whereNull('handled_by')
            ->where('outstanding', true)
            ->paginate(self::PER_PAGE);
        $fulfilledBuffRequests = BuffRequest::where('server_id', $serverId)
            ->whereNotNull('handled_by')
            ->where('outstanding', true)
            ->get();

        foreach ($fulfilledBuffRequests as $buffRequest) {
            if ($buffRequest->updated_at->lt(Carbon::now()->subMinutes(config('buff-requests.minutes-to-disappear')))) {
                BuffRequest::where('id', $buffRequest->id)->update(['outstanding' => false]);
            }
        }

        $fulfilledBuffRequests = BuffRequest::where('server_id', $serverId)
            ->whereNotNull('handled_by')
            ->where('outstanding', true)
            ->paginate(self::PER_PAGE);

        $countMyRequests = BuffRequest::where('handled_by', Auth::user()->id)->count();

        return view('livewire.dashboard', compact(
                'outstandingBuffRequests',
                'fulfilledBuffRequests',
                'countMyRequests')
        );
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

        return redirect('/dashboard');
    }
}
