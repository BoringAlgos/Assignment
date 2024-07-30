<?php

// database/migrations/xxxx_xx_xx_add_claim_state_to_claims_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClaimStateToClaimsTable extends Migration
{
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('claimState'); // Adjust the data type if needed
            $table->string('jobState')->nullable();
        });
    }

    public function down()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn('claimState');
            $table->dropColumn('jobState');
        });
    }
}

