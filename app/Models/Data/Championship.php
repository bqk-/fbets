<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use \DB;
use \URL;

class Championship extends Model {

    protected $table = 'championships';

    public function games(){
        return $this->hasMany('App\Models\Data\Game', 'id_championship', 'id');
    }

    public function sport(){
        return $this->hasOne('App\Models\Data\Sport', 'id', 'id_sport');
    }
}