<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockGroupRepository implements \App\Repositories\Contracts\IGroupRepository
{
    private $seed = 1;
    private $groups = array();
    private $users = array();
    private $notifications = array();
    private $polls = array();
    private $applications = array();
    private $games = array();
    
    public function CreateApplication($iduser, $idgroup, $from, $message) 
    {
        $a = new \App\Models\Data\GroupApplication;
        $a->id_user = $iduser;
        $a->id_group = $idgroup;
        $a->message = $message;
        $a->from = $from;
        
        $this->applications[$idgroup][] = $a;
        return $this->seed;
    }

    public function CreateGroup($name, $description, \DateTime $start, \DateTime $end) 
    {
        $g = new \App\Models\Data\Group;
        $g->id = ++$this->seed;
        $g->name = $name;
        $g->description = $description;
        $g->start = $start;
        $g->end = $end;
        
        $this->groups[$this->seed] = $g;
        
        return $this->seed;
    }

    public function CreateNotification($iduser, $idgroup, $type, $poll) {
        $g = new \App\Models\Data\GroupNotification();
        $g->id_user = $iduser;
        $g->id_group = $idgroup;
        $g->type = $type;
        $g->id_poll = $poll;
        
        $this->notifications[$idgroup][] = $g;
    }

    public function DeleteApplication($user, $group) 
    {
        if(key_exists($group, $this->applications))
        {
            foreach ($this->applications[$group] as $key => $g)
            {
               if($g->id_user == $user)
               {
                    unset($this->applications[$group][$key]);
               }
            }
        }
        
        return null;
    }

    public function Get($id) 
    {
        if(key_exists($id, $this->groups))
        {
            return $this->groups[$id];
        }
        
        return null;
    }

    public function GetApplication($user, $group) {
        if(key_exists($group, $this->applications))
        {
            foreach ($this->applications[$group] as $g)
            {
               if($g->id_user == $user)
               {
                    return $g;
               }
            }
        }
        
        return null;
    }

    public function GetApplications($idgroup, $limit = 20) 
    {
        if(key_exists($idgroup, $this->applications))
        {
            return new \Illuminate\Database\Eloquent\Collection($this->applications[$idgroup]);
        }
        
        return null;
    }

    public function GetNotifications($idgroup, $limit = 20) 
    {
        if(key_exists($idgroup, $this->notifications))
        {
            return new \Illuminate\Database\Eloquent\Collection($this->notifications[$idgroup]);
        }
        
        return null;
    }

    public function GetUsers($idgroup) 
    {
        if(key_exists($idgroup, $this->users))
        {
            return new \Illuminate\Database\Eloquent\Collection($this->users[$idgroup]);
        }
        
        return new \Illuminate\Database\Eloquent\Collection();
    }

    public function PutUserInGroup($user, $group) {        
        $this->users[$group][] = $user;
    }

    public function GetByName($name) 
    {
        foreach ($this->groups as $g)
        {
           if($g->name == $name)
           {
                return new \Illuminate\Database\Eloquent\Collection(array($g));
           }
        }
        
        return null;
    }

    public function GetBetsForGroupAndGame($idgroup, $idgame)
    {
        
    }

    public function GetGroupGames($id, $days)
    {
        
    }

    public function IsInGroup($iduser, $idgroup)
    {
        if(key_exists($idgroup, $this->users))
        {
            foreach ($this->users[$idgroup] as $g)
            {
               if($g == $iduser)
               {
                    return true;
               }
            }
        }
        
        return false;
    }

    public function GroupHasGame($group, $game)
    {
        return false;
    }

    public function AddGameToGroup($game, $group)
    {
        $this->games[$group][] = $game;
    }

    public function GetAll()
    {
        
    }

}