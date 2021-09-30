<?php

namespace App\Http\Controllers\Api\Mixins;

use App\Server;

trait GetServerMixin
{
    private function getServer($serverSnowflake) : Server
    {
        return Server::where('snowflake', $serverSnowflake)->first();
    }
}
