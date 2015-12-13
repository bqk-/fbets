<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model {

    protected $table = 'groups_polls_users';

    public function poll(){
        return $this->belongsTo('App\Models\Data\Poll', 'id_poll');
    }

    public function user(){
        return $this->belongsTo('App\Models\Data\User', 'id_user');
    }
}