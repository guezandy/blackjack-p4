<?php

use Illuminate\Database\Seeder;
use App\Game;
use App\History;
use App\GameHistory;

use App\utilities\BlackJack;

class GameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a first game
        $game = new Game;
        $game->user_id = 1;
        $game->save();

        // First hand
        $history = new History;
        $history->dealer = 'XD,JH';
        $history->user = '4H,7C,XC';
        $history->bet = 10;
        $history->result = BlackJack::HISTORY_STATUS_USER_WIN;
        $history->pot_after_result = 110;
        $history->save();

        // Second hand
        $history = new History;
        $history->dealer = '2D,2H';
        $history->user = '2H,2C,2C';
        $history->bet = 10;
        $history->result = BlackJack::HISTORY_STATUS_DEALER_WIN;
        $history->pot_after_result = 110;
        $history->save();

        // Join table
        $game_history = new GameHistory;
        $game_history->game_id = $game->id;
        $game_history->history_id = $history->id;
        $game_history->save();

    }
}
