<?php

// In the migration file (create_policy_details_table.php):
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicyDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('policy_details', function (Blueprint $table) {
            $table->id();
            $table->string('policy_number')->unique();
            $table->string('email');
            $table->enum('billing_cycle', ['Monthly', 'Quarterly', 'Semester', 'Annually']);
            $table->text('address');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('coverage_details');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('policy_details');
    }

}

