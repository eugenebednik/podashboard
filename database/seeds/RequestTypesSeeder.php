<?php

use App\RequestType;

class RequestTypesSeeder extends \Illuminate\Database\Seeder
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
