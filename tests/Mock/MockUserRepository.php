<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockUserRepository implements \App\Repositories\Contracts\IUserRepository
{
    
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
        return null;
    }

}