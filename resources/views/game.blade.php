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
                        <form method="POST" action="{{ route('gameAction') }}">
                            <input type='hidden' value='{{$game->id}}' name='game_id'>
                            <select name='bet'>

                            </select>
                            <button class='btn btn-primary'>Join Game</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
