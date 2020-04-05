<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sockets', function (Blueprint $table) {
            $table->id();

            // Indicates the owner of the socket
            //$table->integer('user_id');
            $table->bigInteger('user_id');

            // is 
            $table->boolean('switch_state');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sockets');
    }
}
