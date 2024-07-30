<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('policy_id');
            $table->unsignedBigInteger('assigned_to')->nullable(); // Adjust based on how you reference users from the Admin Portal
            $table->string('claim_status');
            $table->json('claim_work')->nullable();
            $table->string('job_status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claims');
    }
}

