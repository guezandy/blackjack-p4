<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameHistoryTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('game_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('game_id')->unsigned();
            $table->integer('history_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_histories');
    }
}
