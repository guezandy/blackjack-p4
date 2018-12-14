<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameHistory extends Model {

    // Thought it was a little wierd that the course notes use game_history and not game_histories
    protected $table = 'game_history';

    public function game() {
        return $this->belongsTo('App\Game', 'game_id');
    }
    public function history() {
        return $this->belongsTo('App\History', 'history_id');
    }
}
