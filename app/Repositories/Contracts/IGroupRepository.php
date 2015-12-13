<?php namespace App\Repositories\Contracts;

interface IGroupRepository
{

    public function GetByName($name);

    public function Get($id);
    
    public function CreateGroup($name, $description);

    public function PutUserInGroup($user, $group);
    
    public function GetUsers($idgroup);
    
    public function GetApplication($user, $group);
    
    public function DeleteApplication($user, $group);
    
    public function CreateNotification($iduser, $idgroup, $type);
    
    public function CreateApplication($iduser, $idgroup, $from, $message);
    
    public function CreateApplicationPoll($application, $iduser, $idgroup);
    
    public function GetNotifications($idgroup, $limit = 20);
    
    public function GetApplications($idgroup, $limit = 20);
}

