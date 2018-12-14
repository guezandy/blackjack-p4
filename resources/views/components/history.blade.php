<div class='history'>
    <p>Dealer has <strong>{{ \App\utilities\BlackJack::handToValue($history->dealer) }}</strong></p>
    @foreach(\App\utilities\BlackJack::handToCardsArray($history->dealer) as $index=>$card)
        <p>Card {{$index + 1}} - {{$card}}</p>
    @endforeach
    <hr>
    <p>Player has <strong>{{ \App\utilities\BlackJack::handToValue($history->user) }} </strong></p>
    @foreach(\App\utilities\BlackJack::handToCardsArray($history->user) as $index=>$card)
        <p>Card {{$index + 1}} - {{$card}}</p>
    @endforeach
    <hr>
    <p>Bet: ${{$history->bet}}</p>
    <p>Result: {{\App\utilities\Blackjack::historyStatusToString($history->result)}}</p>
</div>
