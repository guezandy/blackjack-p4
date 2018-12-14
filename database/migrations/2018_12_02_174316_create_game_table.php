<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\utilities\BlackJack;

class CreateGameTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned(); // Each game will only have a single player for simplicity
            $table->integer('user_pot')->default(100); // Start users with 100$ pot
            $table->integer('status')->default(BlackJack::GAME_STATUS_IN_PROGRESS); // Default is in progress
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
