<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model {
    use SoftDeletes;
    protected $table = 'groups';
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
    
    public function games(){
        return $this->belongsToMany('App\Models\Data\Game', 'groups_games', 'id_group', 'id_game');
    }
}