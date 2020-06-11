<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@podashboard.com',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => \Illuminate\Support\Facades\Hash::make('helloworld'),
            'api_token' => \Illuminate\Support\Str::random(60),
            'is_admin' => true,
            'active' => true,
        ]);

        User::create([
            'name' => 'Test PO Officer',
            'email' => 'test@podashboard.com',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => \Illuminate\Support\Facades\Hash::make('helloworld'),
            'api_token' => \Illuminate\Support\Str::random(60),
            'is_admin' => false,
            'active' => true,
        ]);

        User::create([
            'name' => 'Test Inactive User',
            'email' => 'inactive@podashboard.com',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => \Illuminate\Support\Facades\Hash::make('helloworld'),
            'api_token' => \Illuminate\Support\Str::random(60),
            'is_admin' => false,
            'active' => false,
        ]);
    }
}
