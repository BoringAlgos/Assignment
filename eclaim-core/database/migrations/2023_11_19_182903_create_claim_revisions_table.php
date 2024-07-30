<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimRevisionsTable extends Migration
{
    public function up()
    {
        Schema::create('claim_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims');
            $table->unsignedBigInteger('assigned_to')->nullable(); // Adjust based on how you reference users from the Admin Portal
            $table->string('claim_status');
            $table->string('job_status')->nullable();
            $table->json('claim_work')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_revisions');
    }
}
