<?php

namespace Database\Seeders;

// VehicleDetailsSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PolicyDetailsSeeder extends Seeder
{
    public function run()
    {
        // $faker = Faker::create();

        // foreach (range(1, 10) as $index) {
        //     DB::table('policy_details')->insert([
        //         'policy_number' => $faker->unique()->word,
        //         'email' => $faker->email,
        //         'billing_cycle' => $faker->randomElement(['Monthly', 'Quarterly', 'Semester', 'Annually']),
        //         'address' => $faker->address,
        //         'start_date' => $faker->date,
        //         'end_date' => $faker->date,
        //         'coverage_details' => $faker->text,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Add a specific policy detail
        DB::table('policy_details')->insert([
            'policy_number' => 'PWBL009',
            'email' => 'test@customer.com',
            'billing_cycle' => 'Monthly',
            'address' => 'Test Address',
            'start_date' => '2023-09-12',
            'end_date' => '2024-09-11',
            'coverage_details' => "Policy Details",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

