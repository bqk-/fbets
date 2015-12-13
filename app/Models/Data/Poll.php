<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model {

    protected $table = 'groups_polls';

    public function votes(){
        return $this->hasMany('App\Models\Data\Vote', 'id_poll', 'id');
    }

    public function group(){
        $this->belongsTo('App\Models\Data\Group', 'id_group');
    }

    public function game(){
        $this->belongsTo('App\Models\Data\Game', 'id_game');
    }

    public function user(){
        $this->belongsTo('App\Models\Data\User', 'id_user');
    }

}