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

        // First hand user wins
        $history = new History;
        $history->dealer = 'XD,JH';
        $history->user = '4H,7C,XC';
        $history->bet = 10;
        $history->result = BlackJack::HISTORY_STATUS_USER_WIN;
        $history->pot_after_result = 110;
        $history->save();

        // Join game and history
        $game_history = new GameHistory;
        $game_history->game()->associate($game);
        $game_history->history()->associate($history);
        $game_history->save();

        // After a hand update the current game pot
        $game->user_pot = 110;
        $game->save();

        // Second hand - dealer wins
        $history = new History;
        $history->dealer = '2D,2H';
        $history->user = '2H,2C,2C';
        $history->bet = 10;
        $history->result = BlackJack::HISTORY_STATUS_DEALER_WIN;
        $history->pot_after_result = 100;
        $history->save();

        // Join game and history
        $game_history = new GameHistory;
        $game_history->game()->associate($game);
        $game_history->history()->associate($history);
        $game_history->save();

        // After a hand update the current game pot
        $game->user_pot = 100;
        $game->save();

        // End that game to see data in completed games
        $game->status = BlackJack::GAME_STATUS_COMPLETE;
        $game->save();

        // Start a second game to show a game in the in progress area
        $game = new Game;
        $game->user_id = 1;
        $game->save();

        // First hand for second game
        $history = new History;
        $history->dealer = 'XD,JH';
        $history->user = 'XH,AC';
        $history->bet = 20;
        $history->result = BlackJack::HISTORY_STATUS_USER_WIN;
        $history->pot_after_result = 120;
        $history->save();

        // Join game and history
        $game_history = new GameHistory;
        $game_history->game()->associate($game);
        $game_history->history()->associate($history);
        $game_history->save();

        // After a hand update the current game pot
        $game->user_pot = 120;
        $game->save();

    }
}
