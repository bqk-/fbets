<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockUser implements \App\Services\Contracts\ICurrentUser
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

    public function __construct() {
        $this->Name = 'Tester';
        $this->Pseudo = 'Tester';
        $this->Email = 'test@thibaultmiclo.me';
        $this->Id = 8;
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

