<?php namespace App\Repositories\Contracts;

interface IGroupRepository
{

    public function AddGameToGroup($game, $group);

    public function GroupHasGame($group, $game);

    public function IsInGroup($iduser, $idgroup);

    public function GetBetsForGroupAndGame($idgroup, $idgame);

    public function GetGroupGames($id, $days);

    public function GetByName($name);

    public function Get($id);
    
    public function CreateGroup($name, $description, \DateTime $start, \DateTime $end);

    public function PutUserInGroup($user, $group);
    
    public function GetUsers($idgroup);
    
    public function GetApplication($user, $group);
    
    public function DeleteApplication($user, $group);
    
    public function CreateNotification($iduser, $idgroup, $type, $poll);
    
    public function CreateApplication($iduser, $idgroup, $from, $message);
    
    public function GetNotifications($idgroup, $limit = 20);
    
    public function GetApplications($idgroup, $limit = 20);
}

