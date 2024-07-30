<?php

namespace Database\Seeders;

// database/seeders/ApiKeysTableSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiKeysTableSeeder extends Seeder
{
    public function run()
    {
        // Seed the api_keys table with the specified client_id
        DB::table('api_keys')->insert([
            'client_id' => 'eclaim-system',
            'api_key' => Str::uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

