<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create users table when migration command is called
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('username');

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
