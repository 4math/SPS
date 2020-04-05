<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            // Can be used to log in and restore password
            $table->string('email')->unique();

            // Stores the encrypted user password
            $table->string('password');

            // An API token assigned to user
            // Is used in communication with front end
            $table->string('api_token', 120)->unique();

            // Records the time user been created and updated
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
