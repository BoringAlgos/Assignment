<?php

namespace Database\Seeders;

// VehicleDetailsSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleDetailsSeeder extends Seeder
{
    public function run()
    {
            $policyId = DB::table('policy_details')->inRandomOrder()->first()->id;

            DB::table('vehicle_details')->insert([
                'registration_number' => "DL-10CP9709",
                'make' => "Maruti",
                'model' => "Ciaz AT",
                'year' => "2019",
                'color' => "Red",
                'policy_id' => $policyId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }
}
