<?php namespace App\Services;

use App\Repositories\Contracts\IGroupRepository;
use App\Exceptions\InvalidArgumentException;
use \Auth;
use Exception;

class GroupService
{
    private $_groupRepository;
    
    public function __construct(IGroupRepository $groupRepository)
    {
        $this->_groupRepository = $groupRepository;
    }

    public function CreateGroup($name, $description){
        if(empty($name))
        {
            throw new InvalidArgumentException("name", null);
        }
        
        if(empty($description))
        {
            throw new InvalidArgumentException("description", null);
        }
        
        $this->_groupRepository->CreateGroup($name, $description);

        $this->JoinGroup($g->id);
        $this->GroupNotification(Auth::user()->id, $g->id, JOIN);
        
        return $g->id;
    }

    private function JoinGroup($idgroup)
    {
        $this->_groupRepository->PutUserInGroup(Auth::user()->id, $idgroup);

        $this->GroupNotification(Auth::user()->id, $idgroup, JOIN);
    }
    
    private function AddUserToGroup($iduser, $idgroup)
    {
        $this->_groupRepository->PutUserInGroup($iduser, $idgroup);

        $this->GroupNotification($iduser, $idgroup, JOIN);
    }

    public function QuitGroup($idgroup){
        if(!$this->IsInGroup($idgroup))
        {
            throw new \App\Exceptions\InvalidOperationException('QuitGroup');
        }
        
        $users = $this->_groupRepository->GetUsers($idgroup);
        if(count($users) > 1)
        {
            $this->GroupNotification(Auth::user()->id, $idgroup, QUIT);
        }
        else
        {
            $this->_groupRepository->DeleteGroup($idgroup);
        }
        
        $this->_groupRepository->RemoveUserFromGroup(Auth::user()->id, $idgroup);
    }

    public function ApplicationAcceptGroup($iduser, $idgroup)
    {
        if(!$this->IsInGroup($iduser, $idgroup))
        {
            $a = $this->_groupRepository->GetApplication($iduser, $idgroup);
            if($a != null)
            {
                $this->AddUserToGroup($iduser, $idgroup);
                $this->_groupRepository->DeleteApplication($iduser, $idgroup);
                $this->GroupNotification($iduser, $idgroup, JOIN);
            }
            else
            {
                throw new Exception('No corresponding application');
            }
        }
        else
        {
            throw new Exception('User is already in this group');
        }
    }

    public function IsInGroup($iduser, $idgroup)
    {
        return $this->_groupRepository->IsInGroup($iduser, $idgroup);
    }

    public function HasApplication($iduser, $idgroup)
    {
        $u = $this->_groupRepository->GetApplication($iduser, $idgroup);
        if(!$u){
            return false;
        }
        return true;
    }

    public function GroupNotification($iduser, $idgroup, $type)
    {
        $this->_groupRepository->CreateNotification($iduser, $idgroup, $type);
    }

    public function DeleteGroup($idgroup)
    {
        if($this->IsInGroup(Auth::user()->id, $idgroup))
        {
            $this->_groupRepository->DeleteGroup($idgroup);
        }
        else
        {
            throw new \App\Exceptions\InvalidOperationException("DeleteGroup");   
        }
    }

    public function ApplyForGroup($iduser, $idgroup, $from, $message)
    {
        $group = $this->_groupRepository->Get($idgroup);
        if($group == null)
        {
            throw new InvalidArgumentException("group doesn't exit");
        }

        if(!$this->IsInGroup($iduser, $idgroup) && !$this->HasApplication($iduser, $idgroup)){
            $id = $this->_groupRepository->CreateApplication($iduser, $idgroup, $from, $message);
            $this->_groupRepository->CreateApplicationPoll($id, $iduser, $idgroup);
            if ($from == $iduser) 
            {
                $this->GroupNotification($iduser, $idgroup, APPLY);
            } 
            else 
            {
                $this->GroupNotification($iduser, $idgroup, PROPOSE);
            }

            return $n->id;
        }
        else
        {
            throw new Exception('Cannot apply to this group, already in.');
        }
    }

    public function GetNotifications($idgroup)
    {
        return $this->_groupRepository->GetNotifications($idgroup);
    }

    public function GetApplications($idgroup)
    {
        return $this->_groupRepository->GetApplications($idgroup);
    }

    public function Get($id)
    {
        return $this->_groupRepository->Get($id);
    }

    public function GroupExits($name) 
    {
        return $this->_groupRepository->GetByName($name) === null;
    }

}