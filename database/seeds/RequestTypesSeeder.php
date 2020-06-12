<?php

use App\RequestType;
use Illuminate\Database\Seeder;

class RequestTypesSeeder extends Seeder
{
    public function run()
    {
        foreach (config('request_types') as $key => $requestType) {
            RequestType::create([
                'name' => $requestType
            ]);
        }
    }
}
