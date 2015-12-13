<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

define('JOIN', 1);
define('QUIT', 2);
define('APPLY', 3);
define('PROPOSE', 4);
define('POLL_START', 5);
define('POLL_END', 6);
define('PRIZE_START', 7);
define('PRIZE_END', 8);

class GroupNotification extends Model {

    protected $table = 'groups_notifications';

    public $timestamps = false;

    public function group(){
        return $this->belongsTo('App\Models\Data\Group', 'id_group');
    }

    public function user(){
        return $this->belongsTo('App\Models\Data\User', 'id_user');
    }
}