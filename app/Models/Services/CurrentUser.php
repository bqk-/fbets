<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 23/07/15
 * Time: 17:55
 */

namespace app\Models\Services;

use \Auth;
use App\Models\Data\User;
use App\Services\Contracts\ICurrentUser;

class CurrentUser implements ICurrentUser
{
    private
        $Name,
        $Id,
        $Pseudo,
        $Email,
        $Groups,
        $Applications,
        $LastAction;

    public function __construct()
    {
        if(!Auth::check())
        {
            $this->Id = 0;
            return; 
        }

        $user = User::find(Auth::user()->id);
        if($user == null)
        {
            throw new \App\Exceptions\InvalidOperationException("Logged user doesn't exist");
        }

        $this->Name = $user->display;
        $this->Pseudo = $user->pseudo;
        $this->Id = $user->id;
        $this->Email = $user->email;
        $this->Groups = $user->groups();
        $this->Applications = $user->applications();
        $this->LastAction = $user->last_updated;
    }
    
    public function IsLogged()
    {
        return $this->Id > 0;
    }

    public function GetId()
    {
        return $this->Id;
    }
    
    public function GetName()
    {
        return $this->Name;
    }
    
    public function GetEmail()
    {
        return $this->Email;
    }
    
    public function GetGroups()
    {
        return $this->Groups;
    }
    
    public function GetApplications()
    {
        return $this->Applications;
    }
    
    public function GetLastAction()
    {
        return $this->LastAction;
    }

    public function LogUser($id)
    {
        throw new \App\Exceptions\InvalidOperationException('Testing only!');
    }

}