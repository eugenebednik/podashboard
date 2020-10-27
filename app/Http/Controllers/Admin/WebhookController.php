<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Server;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        $server = Server::findOrFail($request->session()->get('server_id'));
        return view('admin.webhooks')->with(['server' => $server]);
    }
}
