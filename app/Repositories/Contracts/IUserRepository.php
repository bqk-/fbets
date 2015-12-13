<?php namespace App\Repositories\Contracts;

interface IUserRepository
{

    public function GetUserFromRecoverToken($token);

    public function UpdatePassword($userId, $password);

    public function SaveRegisterToken($userId, $token);

    public function DeleteTokenForUser($userId);

    public function GetRecoverByEmail($email);
    
    public function GetUserByEmail($email);

    public function GetUserByPseudo($name);

    public function GetFromToken($token);

    public function DeleteRecoverBeforeDate($date);

    public function MasterLogin($email);
    
    public function Create($name, $email, $display, $pass);
    
    public function GetUserById($id);
}

