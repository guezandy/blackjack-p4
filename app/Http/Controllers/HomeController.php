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
     * @return void
     */
    public function __construct()
    {
        // Require user logged in
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $completed_games = Game::where('user_id', Auth::id())->where('status',
            BlackJack::GAME_STATUS_COMPLETE)->where('deleted_at', null)->get();
        $in_progress_games = Game::where('user_id', Auth::id())->where('status',
            BlackJack::GAME_STATUS_IN_PROGRESS)->where('deleted_at', null)->get();

        // Join game and histories to get last hand for in progress games
        foreach ($in_progress_games as $game) {
            $game_histories = GameHistory::where('game_id', $game->id)->pluck('history_id');
            $game['last_hand'] = History::whereIn('id', $game_histories)->orderBy('id', 'desc')->first();
        }

        // Join game and histories to get all history for completed games
        foreach ($completed_games as $game) {
            $game_histories = GameHistory::where('game_id', $game->id)->pluck('history_id');
            $game['history'] = History::whereIn('id', $game_histories)->orderBy('id', 'desc')->get();
        }

        // Queries for stats
        $games_for_stats_query = Game::where('user_id', Auth::id())->where('deleted_at', null);
        $games_for_stats_ids = $games_for_stats_query->pluck('id');
        $games_for_stats_pot = $games_for_stats_query->sum('user_pot');
        $game_histories_ids = GameHistory::whereIn('game_id', $games_for_stats_ids)->pluck('history_id');
        $histories_count = History::whereIn('id', $game_histories_ids)->count();

        $stats = [
            'games' => count($games_for_stats_ids),
            'hands' => $histories_count,
            'earnings' => $games_for_stats_pot - (count($games_for_stats_ids) * 100)
        ];

        return view('home')
            ->with('completed_games', $completed_games)
            ->with('in_progress_games', $in_progress_games)
            ->with('stats', $stats);
    }

    public function createGame()
    {
        $new_game = new Game;
        $new_game->user_id = Auth::id();
        $new_game->save();

        return redirect()->action('HomeController@index');
    }

    public function endGame()
    {
        $game = Game::where('id', Input::get('game_id'))->first();
        $game->status = BlackJack::GAME_STATUS_COMPLETE;
        $game->save();

        return redirect()->action('HomeController@index');
    }

    public function deleteGame()
    {
        $game = Game::where('id', Input::get('game_id'))->first();
        $game->deleted_at = now();
        $game->save();

        return redirect()->action('HomeController@index');
    }

    public function joinGame()
    {
        $game_id = Input::get('game_id');

        // Am I the user of this game or is game deleted?
        $game = Game::where('id', $game_id)->where('user_id', Auth::id())->where('deleted_at', null)->first();
        if (!isset($game)) {
            return redirect()->back()->with('error_status', 'Cannot join that game');
        }

        $game_histories = GameHistory::where('game_id', $game->id)->pluck('history_id');
        $game['history'] = History::whereIn('id', $game_histories)->where('result', '!=',
            BlackJack::HISTORY_STATUS_IN_PROGRESS)->orderBy('id', 'desc')->get();

        // Joining a game that has a hand in progress
        $game['hand_in_progress'] = History::whereIn('id', $game_histories)->where('result',
            BlackJack::HISTORY_STATUS_IN_PROGRESS)->orderBy('id', 'desc')->first();

        return view('game')->with('game', $game);
    }

    public function startHand()
    {
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

    public function gameAction()
    {
        $game_id = Input::get('game_id');
        $action = Input::get('action');

        $game = Game::findOrFail($game_id);
        $game_histories = GameHistory::where('game_id', $game_id)->pluck('history_id');
        $hand_in_progress = History::whereIn('id', $game_histories)->where('result',
            BlackJack::HISTORY_STATUS_IN_PROGRESS)->orderBy('id', 'desc')->first();

        // Create an instance of the blackjack game class by passing in the current hand in progress and
        // game. This class handles the game play.
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
