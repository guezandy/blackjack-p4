@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Instructions</div>
                <div class="card-body">
                    <ul>
                        <li>Click "Create Game" to start a game - meant to resemble joining a table</li>
                        <li>Every game only has one player (YOU) and your $ starts at $100</li>
                        <li>Stats include only non-deleted games</li>
                        <li>You can join any game in progress</li>
                        <li>You can end any game in progress - not allow any more hands</li>
                        <li>You can delete any game in progress or ended games - which will remove them from stats</li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Create Game</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('createGame') }}">
                        @csrf
                        <button class='btn btn-primary'>Create Game</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Stats</div>
                <div class="card-body">
                    <ul>
                        <li>Games ended or in progress: {{$stats['games']}}</li>
                        <li>Hands played: {{$stats['hands']}}</li>
                        <li>Money made: ${{$stats['earnings']}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class='col-md-8'>
            <div class='col-sm-12'>
                <div class='col-sm-12'>
                    @if(count($in_progress_games) > 0)
                        <h2>Game(s) in Progress </h2>
                    @else
                        <h2>No games in progress - click create game to start.</h2>
                    @endif
                    @if(isset($error_status))
                        <p class='text-danger'>{{$error_status}}</p>
                    @endif
                </div>
                <div class='row'>
                    @foreach($in_progress_games as $game)
                        @component('components.game', ['game'=> $game])@endcomponent
                    @endforeach
                </div>
            </div>
            <hr>
            <div class='col-sm-12'>
                <div class='col-sm-12'>
                    @if(count($completed_games) > 0)
                        <h2>Completed games</h2>
                    @else
                        <h2>No game(s) completed - Once you end a game it'll be saved here.</h2>
                    @endif
                    @if(isset($error_status))
                        <p class='text-danger'>{{$error_status}}</p>
                    @endif
                </div>
                <div class='row'>
                    @foreach($completed_games as $game)
                        @component('components.game', ['game'=> $game])@endcomponent
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
