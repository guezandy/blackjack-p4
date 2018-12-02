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

    public function joinGame() {
        $game_id = Input::get('game_id');

        // Am I the user of this game?
        $game = Game::where('id', $game_id)->where('user_id', Auth::id())->first();
        if(!isset($game)) {
            return redirect()->back()->with('error_status', 'Cannot join that game');
        }

        $game_histories = GameHistory::where('game_id', $game->id)->pluck('history_id');
        $game['history'] = History::whereIn('id', $game_histories)->orderBy('id', 'desc')->get();

        return view('game')->with('game', $game);

    }
}
