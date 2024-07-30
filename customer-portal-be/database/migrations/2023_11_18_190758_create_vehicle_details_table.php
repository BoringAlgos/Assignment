<?php

// Run this command to create the migration file:
// php artisan make:migration create_vehicle_details_table

// In the migration file:
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('vehicle_details', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->string('color');
            $table->unsignedBigInteger('policy_id');
            $table->foreign('policy_id')->references('id')->on('policy_details');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_details');
    }

}

