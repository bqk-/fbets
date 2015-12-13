<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use \URL;

class Sport extends Model {

    protected $table = 'sports';

    public function championships(){
        return $this->hasMany('App\Models\Data\Championship', 'id_sport', 'id');
    }
}