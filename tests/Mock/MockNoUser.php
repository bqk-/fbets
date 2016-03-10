<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockNoUser implements \App\Services\Contracts\ICurrentUser
{
    public function GetApplications() {
        
    }

    public function GetEmail() {
        return $this->Email;
    }

    public function GetGroups() {
        
    }

    public function GetId() {
        return $this->Id;
    }

    public function GetLastAction() {
        
    }

    public function GetName() {
        return $this->Name;
    }
    
    public function LogUser($id) {
        //
    }

    public function __construct() {
        $this->Id = 0;
    }

    private
        $Name,
        $Id,
        $Pseudo,
        $Email,
        $Groups,
        $Applications,
        $LastAction;
}

