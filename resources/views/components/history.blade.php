<p>Dealer:
<ul>
    @foreach(\App\utilities\BlackJack::handToCardsArray($history->dealer) as $index=>$card)
        <li>Card {{$index + 1}} - {{$card}}</li>
    @endforeach
</ul>
</p>
<p>Player:
<ul>
@foreach(\App\utilities\BlackJack::handToCardsArray($history->user) as $index=>$card)
    <li>Card {{$index + 1}} - {{$card}}</li>
@endforeach
</ul>
</p>
<p>Bet: ${{$history->bet}}</p>
<p>Result: {{\App\utilities\Blackjack::historyStatusToString($history->result)}}</p>
