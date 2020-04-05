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

            $table->bigInteger('user_id')->unsigned()->default(1);
            //$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name');
            $table->text('description');
            $table->boolean('switch_state')->default(false);
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
