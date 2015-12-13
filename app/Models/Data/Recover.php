<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Recover extends Model {

    protected $table = 'users_reminder';
    protected $primaryKey = "users_id";

    public function user(){
        return $this->belongsTo('App\Models\Data\User');
    }

}