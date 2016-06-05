<?php namespace App\Services\Contracts;

interface ICurrentUser
{
    public function __construct();
    
    public function GetId();
    
    public function GetName();
    
    public function GetEmail();
    
    public function GetGroups();
    
    public function GetApplications();
    
    public function GetLastAction();
    
    public function LogUser($id);
}



