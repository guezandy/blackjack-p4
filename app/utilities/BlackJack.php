<?php
namespace App\utilities;

class BlackJack
{
    const HISTORY_STATUS_IN_PROGRESS = 0;
    const HISTORY_STATUS_DEALER_WIN = 1;
    const HISTORY_STATUS_USER_WIN = 2;
    const HISTORY_STATUS_PUSH = 3;

    const GAME_STATUS_IN_PROGRESS = 0;
    const GAME_STATUS_COMPLETE = 1;

    // Can probably add more actions if I continue building
    const ACTION_HOLD = 0;
    const ACTION_HIT = 1;

    const SUITS = ['H', 'C', 'D', 'S'];
    const VALUES = ['A', '2', '3', '4', '5', '6', '7', '8', '9', 'X', 'J', 'Q', 'K'];

    public $history;
    public $game;

    // Construct a BlackJack instance taking in a History object
    public function __construct($history, $game)
    {
        // TODO make sure $history instance if of type App\History
        $this->history = $history;

        // TODO make sure $history instance if of type App\Game
        $this->game = $game;
    }

    public function performActionHit()
    {
        $new_user_hand = $this->history->user . ',' . BlackJack::deal1();
        $this->history->user = $new_user_hand;
        $this->history->save();

        // Did user pass 21
        if (BlackJack::didUserLose()) {
            BlackJack::setUserLost();
        }
    }

    public function performActionHold()
    {
        // if blackjack and only 2 cards then you automatically win
        if (BlackJack::didUser21() && strlen($this->history->user) == 4) {
            BlackJack::setUserBlackJack();

            return;
        }

        // Dealer hits until he's done
        while (BlackJack::doesDealerHit()) {
            $this->history->dealer = $this->history->dealer . ',' . BlackJack::deal1();
            $this->history->save();
        }

        // check if dealer busted
        if (BlackJack::didDealerLose()) {
            BlackJack::setUserWin();

            return;
        }
        // check for push
        if (BlackJack::didUserDealerPush()) {
            BlackJack::setUserPush();

            return;
        }
        // who has the highest number
        if (BlackJack::didUserBeatDealer()) {
            BlackJack::setUserWin();

        } else {
            BlackJack::setUserLost();
        }
        return;
    }

    public function didUserBeatDealer()
    {
        return BlackJack::handToValue($this->history->user) > BlackJack::handToValue($this->history->dealer);
    }

    public function didUserDealerPush()
    {
        return BlackJack::handToValue($this->history->user) == BlackJack::handToValue($this->history->dealer);
    }

    public function didUserLose()
    {
        return BlackJack::handToValue($this->history->user) > 21;
    }

    public function didUser21()
    {
        return BlackJack::handToValue($this->history->user) == 21;
    }

    public function didDealer21()
    {
        return BlackJack::handToValue($this->history->dealer) == 21;
    }

    public function didDealerLose()
    {
        return BlackJack::handToValue($this->history->dealer) > 21;
    }

    public function doesDealerHit()
    {
        // Dealer hits on soft 17
        $dealer_hand_value = BlackJack::handToValue($this->history->dealer);

        return $dealer_hand_value < 17 || ($dealer_hand_value == 17 && strpos($this->history->dealer, 'A'));
    }

    public function setUserLost()
    {
        $new_pot = $this->game->user_pot - $this->history->bet;
        $this->game->user_pot = $new_pot;
        // if pot < 0 then end the game
        if ($new_pot <= 0) {
            $this->game->status = BlackJack::GAME_STATUS_COMPLETE;
        }
        $this->game->save();

        $this->history->result = BlackJack::HISTORY_STATUS_DEALER_WIN;
        $this->history->pot_after_result = $new_pot;
        $this->history->save();
    }

    public function setUserWin()
    {
        $new_pot = $this->game->user_pot + $this->history->bet;
        $this->game->user_pot = $new_pot;
        $this->game->save();

        $this->history->result = BlackJack::HISTORY_STATUS_USER_WIN;
        $this->history->pot_after_result = $new_pot;
        $this->history->save();
    }

    public function setUserBlackJack()
    {
        $new_pot = $this->game->user_pot + (2 * $this->history->bet);
        $this->game->user_pot = $new_pot;
        $this->game->save();

        $this->history->result = BlackJack::HISTORY_STATUS_USER_WIN;
        $this->history->pot_after_result = $new_pot;
        $this->history->save();
    }

    public function setUserPush()
    {
        $this->history->result = BlackJack::HISTORY_STATUS_PUSH;
        $this->history->save();
    }

    public static function historyStatusToString($status)
    {
        switch ($status) {
            case BlackJack::HISTORY_STATUS_DEALER_WIN:
                return 'Dealer win';
            case BlackJack::HISTORY_STATUS_USER_WIN:
                return 'User win';
            case BlackJack::HISTORY_STATUS_PUSH:
                return 'Push';
            case BlackJack::HISTORY_STATUS_IN_PROGRESS:
                return 'In progress';
            default:
                return 'Error';
        }
    }

    public static function handToCardsArray($hand)
    {
        $cards = explode(',', $hand);
        $card_strings = [];
        foreach ($cards as $card) {
            array_push($card_strings, BlackJack::cardStringToLegibleString($card));
        }

        return $card_strings;
    }

    public static function handToValue($hand)
    {
        $cards = explode(',', $hand);
        $card_strings = [];
        $value = 0;
        foreach ($cards as $card) {
            $card_value = $card[0];
            if ($card_value == 'X' || $card_value == 'J' || $card_value == 'Q' || $card_value == 'K') {
                $card_value = 10;
            }
            if ($card_value == 'A') {
                $card_value = 11;
            }
            $value += intval($card_value);
        }
        // Modify A to 1 if over 21 and has an A
        if ($value > 21 && strpos($hand, 'A')) {
            $value = $value - 10;
        }

        return $value;
    }

    public static function cardStringToLegibleString($card)
    {
        $suit_string = '';

        $value = $card[0];
        $suit = $card[1];

        if ($value == 'X') {
            $value = '10';
        }
        switch ($suit) {
            case 'C':
                $suit_string = 'clovers';
                break;
            case 'H':
                $suit_string = 'hearts';
                break;
            case 'S':
                $suit_string = 'spades';
                break;
            case 'D':
                $suit_string = 'diamonds';
                break;
            default:
                $suit_string = 'error';
                break;
        }

        return $value . ' of ' . $suit_string;
    }

    public static function deal1()
    {
        // Probably way better ways of doing this - keeping track of what cards have been seen already but i'll keep it simple
        $value = rand(0, 12);
        $suit = rand(0, 3);

        return BlackJack::VALUES[$value] . BlackJack::SUITS[$suit];
    }

    public static function deal2()
    {
        return BlackJack::deal1() . ',' . BlackJack::deal1();
    }

}
