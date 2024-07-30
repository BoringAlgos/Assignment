<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('claim_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims');
            $table->string('document_type');
            $table->string('link');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_documents');
    }
}

