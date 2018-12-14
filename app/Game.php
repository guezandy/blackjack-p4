<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {

    /**
     * Get the GameHistory pivot rows for a game.
     */
    public function history()
    {
        return $this->hasMany('App\GameHistory', 'game_id'); // Tried to make this work but couldn't
    }
}
