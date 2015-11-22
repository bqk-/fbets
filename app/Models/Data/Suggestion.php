<?php namespace App\Models\Data;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model {

    protected $table = 'suggestions';

    public function user(){
        return $this->belongsTo('App\Models\Data\User', 'id_user');
    }

    public function championship(){
        return $this->belongsTo('App\Models\Data\Championship', 'id_championship');
    }
}