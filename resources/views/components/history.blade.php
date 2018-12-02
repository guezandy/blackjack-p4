<p>Dealer:
<ul>
    @foreach(\App\utilities\BlackJack::handToCardsArray($history->dealer) as $index=>$card)
        <li>Card {{$index + 1}} - {{$card}}</li>
    @endforeach
</ul>
Value: {{ \App\utilities\BlackJack::handToValue($history->dealer) }}
</p>
<hr>
<p>Player:
<ul>
@foreach(\App\utilities\BlackJack::handToCardsArray($history->user) as $index=>$card)
    <li>Card {{$index + 1}} - {{$card}}</li>
@endforeach
</ul>
Value: {{ \App\utilities\BlackJack::handToValue($history->user) }}
</p>
<p>Bet: ${{$history->bet}}</p>
<p>Result: {{\App\utilities\Blackjack::historyStatusToString($history->result)}}</p>
