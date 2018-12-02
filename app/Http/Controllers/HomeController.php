<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\History;
use App\Game;
use App\GameHistory;

use App\utilities\BlackJack;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $completed_games = Game::where('user_id', Auth::id())->where('status', BlackJack::GAME_STATUS_COMPLETE)->get();
        $in_progress_games = Game::where('user_id', Auth::id())->where('status', BlackJack::GAME_STATUS_IN_PROGRESS)->get();

        foreach($in_progress_games as $game) {
            $game_histories = GameHistory::where('game_id', $game->id)->pluck('history_id');
            $game['last_hand'] = History::whereIn('id', $game_histories)->orderBy('id', 'desc')->first();
        }
        return view('home')->with('completed_games', $completed_games)->with('in_progress_games', $in_progress_games);
    }

    public function createGame() {

        $new_game = new Game;
        $new_game->user_id = Auth::id();
        $new_game->save();

        return redirect()->action('HomeController@index');
    }

    public function endGame() {

        $game = Game::where('id', Input::get('game_id'))->first();
        $game->status = BlackJack::GAME_STATUS_COMPLETE;
        $game->save();

        return redirect()->action('HomeController@index');
    }

    public function joinGame() {
        $game_id = Input::get('game_id');

        // Am I the user of this game?
        $game = Game::where('id', $game_id)->where('user_id', Auth::id())->first();
        if(!isset($game)) {
            return redirect()->back()->with('error_status', 'Cannot join that game');
        }

        $game_histories = GameHistory::where('game_id', $game->id)->pluck('history_id');
        $game['history'] = History::whereIn('id', $game_histories)->where('result', '!=', BlackJack::HISTORY_STATUS_IN_PROGRESS)->orderBy('id', 'desc')->get();
        // First game with status = in progress
        $game['hand_in_progress'] = History::whereIn('id', $game_histories)->where('result', BlackJack::HISTORY_STATUS_IN_PROGRESS)->orderBy('id', 'desc')->first();

        return view('game')->with('game', $game);

    }

    public function startHand() {
        $game_id = Input::get('game_id');
        $bet = Input::get('bet');

        $new_history = new History;
        $new_history->bet = $bet;
        $new_history->dealer = BlackJack::deal2();
        $new_history->user = BlackJack::deal2();
        $new_history->save();


        $new_game_history = new GameHistory;
        $new_game_history->game_id = $game_id;
        $new_game_history->history_id = $new_history->id;
        $new_game_history->save();

        return redirect()->back();
    }

    public function gameAction() {
        $game_id = Input::get('game_id');
        $action = Input::get('action');

        $game = Game::findOrFail($game_id);
        $game_histories = GameHistory::where('game_id', $game_id)->pluck('history_id');
        $hand_in_progress = History::whereIn('id', $game_histories)->where('result', BlackJack::HISTORY_STATUS_IN_PROGRESS)->orderBy('id', 'desc')->first();

        $blackjack = new BlackJack($hand_in_progress, $game);
        switch ($action) {
            case BlackJack::ACTION_HIT:
                $blackjack->performActionHit();
                break;
            case BlackJack::ACTION_HOLD:
                $blackjack->performActionHold();
                break;
            default:
                break;
        }

        return redirect()->back();
    }
}
