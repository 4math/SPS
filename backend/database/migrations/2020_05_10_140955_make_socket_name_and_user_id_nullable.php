<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSocketNameandUserIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sockets', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->bigInteger('user_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sockets', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->bigInteger('user_id')->unsigned()->nullable(false)->change();
        });
    }
}
