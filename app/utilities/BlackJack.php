<?php
namespace App\utilities;

class BlackJack
{
    const HISTORY_STATUS_NOOP = 0;
    const HISTORY_STATUS_DEALER_WIN = 1;
    const HISTORY_STATUS_USER_WIN = 2;
    const HISTORY_STATUS_PUSH = 3;

    const GAME_STATUS_IN_PROGRESS = 0;
    const GAME_STATUS_COMPLETE = 1;

    public static function historyStatusToString($status) {
        switch ($status) {
            case BlackJack::HISTORY_STATUS_DEALER_WIN:
                return 'Dealer win';
            case BlackJack::HISTORY_STATUS_USER_WIN:
                return 'User win';
            case BlackJack::HISTORY_STATUS_PUSH:
                return 'Push';
            default:
                return 'Error';
        }
    }

    public static function handToCardsArray($hand) {
        $cards = explode(',', $hand);
        $card_strings = [];
        foreach($cards as $card) {
            $suit_string = '';

            $value = $card[0];
            $suit = $card[1];

            if ($value == 'X') {
                $value = '10';
            }
            switch($suit) {
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
            array_push($card_strings, $value . ' of ' . $suit_string);
        }
        return $card_strings;
    }

}
