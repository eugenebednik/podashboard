<?php

use App\Alliance;
use Illuminate\Database\Seeder;

class AlliancesTableSeeder extends Seeder
{
    public function run()
    {
        foreach (config('alliances') as $key => $alliance) {
            Alliance::create([
                'name' => $alliance,
            ]);
        }
    }
}
