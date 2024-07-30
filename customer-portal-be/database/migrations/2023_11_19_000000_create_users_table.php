<?php
// In a migration file (e.g., create_users_table.php)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('policy_id');
            $table->timestamps();

            $table->foreign('policy_id')->references('id')->on('policy_details');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
