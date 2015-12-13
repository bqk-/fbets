<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class GroupApplication extends Model {

    protected $table = 'groups_requests';

    public function group(){
        return $this->belongsTo('App\Models\Data\Group', 'id_group');
    }

    public function user(){
        return $this->belongsTo('App\Models\Data\User', 'id_user');
    }
}