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
                    <div class="card-header">History</div>
                    <div class="card-body">
                        @if(count($game['history']) == 0)
                            <p>No history yet</p>
                        @else
                            @foreach($game['history'] as $index=>$history)
                                @component('components.history', ['history'=> $history])@endcomponent
                                <hr>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class='col-sm-8'>
                <div class='card'>
                    <div class="card-header">Game Area</div>
                    <div class="card-body">
                        @if(isset($game['hand_in_progress']))
                            @component('components.history', ['history' => $game['hand_in_progress']])@endcomponent
                            <form method="POST" action="{{ route('gameAction') }}">
                                @csrf
                                <input type='hidden' value='{{$game->id}}' name='game_id'>
                                <div class="form-group row">
                                    <label for="action" class='col-sm-2'>What to do: </label>
                                    <select class="form-control col-sm-6" id="action" name='action' required>
                                        <option value='{{\App\utilities\BlackJack::ACTION_HIT}}'>Hit</option>
                                        <option value='{{\App\utilities\BlackJack::ACTION_HOLD}}'>Hold</option>
                                    </select>
                                    <div class='col-sm-2'>
                                        <button class='btn btn-primary' type='submit'>Perform Action</button>
                                    </div>
                                </div>
                            </form>
                        @else
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
