@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class='col-sm-4'>
                <div class='card'>
                    <div class="card-header">Current user funds</div>
                    <div class="card-body">
                        ${{$game->user_pot}}
                    </div>
                </div>
                <div class='card'>
                    <div class="card-header">Instructions</div>
                    <div class="card-body">
                        Welcome to the game play area
                        <ul>
                            <li>Current user funds shows how much money you have left in this game</li>
                            <li>History shows a brief history of previous hands played in this game</li>
                            <li>In the game area you play black jack</li>
                            <li>BlackJack pays 2 to 1 for integer math purposes</li>
                        </ul>
                        Game play
                        <ul>
                            <li>Select your initial bet</li>
                            <li>See your hand and the dealers hand - Both dealer cards show - just for simplicity</li>
                            <li>You can push if the dealer has 21 at the start - not like usual blackjack again for simplicity</li>
                            <li>Perform ACTION - either hit or hold</li>
                            <li><strong>Hit</strong> will draw you a new card</li>
                            <li><strong>Hold</strong> will hold and let the dealer hit til win or bust</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='card'>
                    <div class="card-header">Game Area</div>
                    @if(isset($game['hand_in_progress']))
                        <div class="card-body">
                            @component('components.history', ['history' => $game['hand_in_progress']])@endcomponent
                        </div>
                        <div class='card-footer'>
                            <form method="POST" action="{{ route('gameAction') }}">
                                @csrf
                                <input type='hidden' value='{{$game->id}}' name='game_id'>
                                <div class="form-group row">
                                    <select class="form-control col-sm-6" id="action" name='action' required>
                                        <option value='{{\App\utilities\BlackJack::ACTION_HIT}}'>Hit</option>
                                        <option value='{{\App\utilities\BlackJack::ACTION_HOLD}}'>Hold</option>
                                    </select>
                                    <div class='col-sm-2'>
                                        <button class='btn btn-primary' type='submit'>Perform Action</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="card-body">
                            <form method="POST" action="{{ route('startHand') }}">
                                @csrf
                                <input type='hidden' value='{{$game->id}}' name='game_id'>
                                <div class="form-group row">
                                    <label for="bet" class='col-sm-2'>Bet</label>
                                    <select class="form-control col-sm-6" id="bet" name='bet' required>
                                        <option value='10'>10</option>
                                        <option value='20'>20</option>
                                        <option value='40'>40</option>
                                    </select>
                                    <div class='col-sm-2'>
                                        <button class='btn btn-primary' type='submit'>Place bet</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                     @endif
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='card'>
                    <div class="card-header">History</div>
                    <div class="card-body">
                        @if(count($game['history']) == 0)
                            <p>No history yet</p>
                        @else
                            @foreach($game['history'] as $index=>$history)
                                <div class='game-play-history'>
                                    @component('components.history', ['history'=> $history])@endcomponent
                                </div>
                                <hr>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
