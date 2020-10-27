<?php

namespace App\Http\Controllers\Api\Mixins;

use App\Server;

trait GetServerMixin
{
    /**
     * @param $serverSnowflake
     *
     * @return \Illuminate\Http\JsonResponse|Server
     */
    private function getServer($serverSnowflake)
    {
        return Server::where('snowflake', $serverSnowflake)->first();
    }
}
