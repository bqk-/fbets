<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockUserRepository implements \App\Repositories\Contracts\IUserRepository
{
    private $users = array();
    private $id = 1;
    
    public function __construct()
    {
        $u = new \App\Models\Data\User();
        $u->id = ++$this->id;
        $u->points = 1000;
        $this->users[$this->id] = $u;
        
        $u2 = new \App\Models\Data\User();
        $u2->id = ++$this->id;
        $u2->points = 1000;
        $this->users[$this->id] = $u2;
        
        $u3 = new \App\Models\Data\User();
        $u3->id = ++$this->id;
        $u3->points = 1000;
        $this->users[$this->id] = $u3;
    }
    
    public function AttemptLogin($email, $password, $remember) {
        return true;
    }

    public function Create($name, $email, $display, $pass) {
        return 8;
    }

    public function DeleteRecoverBeforeDate($date) {
        
    }

    public function DeleteTokenForUser($userId) {
        
    }

    public function GetFromToken($token) {
    
    }

    public function GetRecoverByEmail($email) {
        
    }

    public function GetUserByPseudo($name) {
        return null;
    }

    public function GetUserFromRecoverToken($token) {
        
    }

    public function MasterLogin($email) {
        
    }

    public function SaveRegisterToken($userId, $token) {
        
    }

    public function UpdatePassword($userId, $password) {
        
    }

    public function GetUserByEmail($email) {
        return null;
    }

    public function GetUserById($id) {
        if(key_exists($id, $this->users)){
            return $this->users[$id];
        }
    }

    public function AddPoints($userId, $points)
    {
        if(key_exists($userId, $this->users)){
            $this->users[$userId]->points += $points;
        }
    }

    public function RemovePoints($userId, $points)
    {
        if(key_exists($userId, $this->users)){
            $this->users[$userId]->points -= $points;
        }
    }

    public function GetTopUsersPoints()
    {
        //only for view
    }

}