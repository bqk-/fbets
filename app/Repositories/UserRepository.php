<?php namespace App\Repositories;

use App\Repositories\Contracts\IUserRepository;
use \Hash;

class UserRepository implements IUserRepository
{
    public function GetUserByPseudo($name)
    {
        $user = User::where('pseudo', '=', $name)->first();

        return $user;
    }
    
    public function GetUserByEmail($email)
    {
        $user = User::where('pseudo', '=', $email)->first();

        return $user;
    }
    
    public function GetUserById($id)
    {
        $user = User::find($id);

        return $user;
    }

    public function Create($name, $email, $display, $pass) {
        $user = new User();
        $user->pseudo     = $name;
        $user->email    = $email;
        $user->display    = $display;
        $user->password = Hash::make($pass);
        $user->save();
        
        return $user->id;
    }

    public function MasterLogin($email) 
    {
        $id = User::where('email','=',$email)->first();
        $user = User::find($id->id);
        
        return $user;
    }

    public function DeleteRecoverBeforeDate($date) 
    {
        Recover::where('created_at', '<', $date->format('Y-m-d H:i:s'))
                ->delete();
    }

    public function GetFromToken($token) 
    {
        return Recover::where('token', '=', $token)->first();
    }

    public function GetRecoverByEmail($email) 
    {
        return Recover::where('email', '=', $email)->first();
    }

    public function DeleteTokenForUser($userId) 
    {
        return Recover::where('users_id', '=', $userId)->delete();
    }

    public function SaveRegisterToken($userId, $token) 
    {
        $recover = new Recover;
        $recover->users_id = $userId;
        $recover->token = $token;
        $recover->save();
        
        return $recover->id;
    }

    public function UpdatePassword($userId, $password) 
    {
        $user = User::find($userId);
        if($user == null)
        {
            throw new \App\Exceptions\InvalidOperationException('unknown user, suspicious');
        }
    
        $user->password = $password;
        $user->save();
    }

    public function GetUserFromRecoverToken($token) 
    {
        $recover = Recover::where('token', '=', $token)->first();
        if($recover == null)
        {
            throw new \App\Exceptions\InvalidOperationException('outdated token');
        }
        
        $user = User::find($recover->users_id);
        if($user == null)
        {
            throw new \App\Exceptions\InvalidOperationException('user not found from token');
        }
        
        $recover->delete();
        return $user;
    }

    public function AddPoints($userId, $points)
    {
        $user = $this->GetUserById($userId);
        $user->increment('points', $points);
        $user->save();
    }

    public function RemovePoints($userId, $points)
    {
        $user = $this->GetUserById($userId);
        $user->decrement('points', $points);
        $user->save();
    }

}

