<?php

use App\User;
use App\Server;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $server = Server::create([
            'snowflake' => \Illuminate\Support\Str::random(18),
            'name' => 'Dummy Server',
            'is_active' => true,
        ]);

        $user = new User();
        $user->name = 'Admin';
        $user->discord_id = '00000000000000000';
        $user->email = 'admin@podashboard.test';
        $user->api_token = \Illuminate\Support\Str::random(60);
        $user->server()->associate($server);
        $user->save();
    }
}
