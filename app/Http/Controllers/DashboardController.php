<?php

namespace App\Http\Controllers;

use App\BuffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    const PER_PAGE = 20;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'active']);
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
        }

        return redirect('/dashboard');
    }
}
