<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Server;
use App\Services\DiscordService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
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
        $roles = $this->discordService->getGuildRoles($server->snowflake);

        return view('admin.roles')->with(['roles' => $roles, 'server' => $server]);
    }
}
