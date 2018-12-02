@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Create Game</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('createGame') }}">
                        @csrf
                        <button class='btn btn-primary'>Create Game</button>
                    </form>
                </div>
            </div>
            <div class='card'>
                <div class="card-header">Your stats</div>
                <div class="card-body">
                </div>
            </div>
        </div>
        <div class='col-md-8'>
            @if(count($in_progress_games) > 0)
                <div class='col-sm-12'>
                    Games in Progress <br>
                    @if(isset($error_status))
                        <p class='text-danger'>{{$error_status}}</p>
                    @endif
                </div>
            @endif
            <div class='row'>
            @foreach($in_progress_games as $game)
                <div class='col-sm-6'>
                    <div class='card'>
                        <div class="card-header">Current Pot: ${{$game->user_pot}}</div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('joinGame') }}">
                                <input type='hidden' value='{{$game->id}}' name='game_id'>
                                <button class='btn btn-primary'>Join Game</button>
                            </form>
                            @if(isset($game['last_hand']))
                                <div class='row'>
                                    <div class='col-sm-12'>
                                        <p>Last Hand:</p>
                                        @component('components.history', ['history'=> $game['last_hand']])@endcomponent
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
