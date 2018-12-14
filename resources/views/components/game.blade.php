<div class='col-sm-6'>
    <div class='card'>
        <div class="card-header">Current Pot: ${{$game->user_pot}}</div>
        <div class="card-body">
            @if(isset($game['last_hand']))
                <div class='row'>
                    <div class='col-sm-12'>
                        <h3 class='text-center'>Last Hand</h3>
                        <hr>
                        @component('components.history', ['history'=> $game['last_hand']])@endcomponent
                    </div>
                </div>
            @elseif(isset($game['history']) && count($game['history']) > 0)
                @foreach($game['history'] as $history)
                    <div class='row'>
                        <div class='col-sm-12'>
                            <hr>
                            @component('components.history', ['history'=> $history])@endcomponent
                        </div>
                    </div>
                @endforeach
            @else
                <div class='row'>
                    <div class='col-sm-12'>
                        <p>This game has no play history</p>
                    </div>
                </div>
            @endif
        </div>
        <div class='card-footer'>
            @if($game->user_pot > 0 && $game->status == \App\utilities\BlackJack::GAME_STATUS_IN_PROGRESS)
                <form method="GET" action="{{ route('joinGame') }}">
                    <input type='hidden' value='{{$game->id}}' name='game_id'>
                    <button class='btn btn-primary'>Join Game</button>
                </form>
            @endif
            @if($game->status == \App\utilities\BlackJack::GAME_STATUS_IN_PROGRESS)
                <form method="POST" action="{{ route('endGame') }}">
                    @csrf
                    <input type='hidden' value='{{$game->id}}' name='game_id'>
                    <button class='btn btn-warning'>End Game</button>
                </form>
            @endif
            <form method="POST" action="{{ route('deleteGame') }}">
                @csrf
                <input type='hidden' value='{{$game->id}}' name='game_id'>
                <button class='btn btn-danger'>Delete Game</button>
            </form>
        </div>
    </div>
</div>
