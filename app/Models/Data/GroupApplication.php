<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupApplication extends Model {
    use SoftDeletes;
    protected $table = 'groups_requests';
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public function group(){
        return $this->belongsTo('App\Models\Data\Group', 'id_group');
    }

    public function user(){
        return $this->belongsTo('App\Models\Data\User', 'id_user');
    }
}