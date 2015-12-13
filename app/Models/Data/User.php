<?php namespace App\Models\Data;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

		/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function recover(){
		return $this->hasOne('App\Models\Data\Recover', 'users_id');
	}

    public function votes(){
        return $this->hasMany('App\Models\Data\Vote', 'id_user', 'id');
    }

    public function bets(){
        return $this->hasMany('App\Models\Data\Bet', 'id_user', 'id');
    }

    public function groups(){
        return $this->belongsToMany('App\Models\Data\Group', 'groups_users', 'id_user', 'id_group');
    }
}
