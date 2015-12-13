<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

    protected $table = 'groups';

    public function users(){
        return $this->belongsToMany('App\Models\Data\User', 'groups_users', 'id_group', 'id_user');
    }

    public function polls(){
        return $this->hasMany('App\Models\Data\Poll', 'id_group', 'id');
    }

    public function applications(){
        return $this->hasMany('App\Models\Data\GroupApplication', 'id_group', 'id');
    }

    public function notifications(){
        return $this->hasMany('App\Models\Data\GroupNotification', 'id_group', 'id');
    }
}