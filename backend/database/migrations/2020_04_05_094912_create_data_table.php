<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration
{
    // TODO: Rename `data` to `measurements` 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('socket_id')->unsigned();
            // TODO: change deleting method, cascade delete not working after Laravel 5
            $table->foreign('socket_id')->references('id')->on('sockets')->onDelete('cascade');

            $table->float('power');
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
        Schema::dropIfExists('data');
    }
}
