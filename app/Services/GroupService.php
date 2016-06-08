<?php namespace App\Services;

use App\Repositories\Contracts\IGroupRepository;
use App\Repositories\Contracts\IPollRepository;
use App\Repositories\Contracts\IGameRepository;
use App\Services\Contracts\ICurrentUser;
use App\Exceptions\InvalidArgumentException;
use \Validator;
use Exception;
use App\Models\Types\NotificationTypes;

class GroupService
{
    private $_groupRepository;
    private $_currentUser;
    private $_pollRepository;
    private $_gameRepository;

    private $DELAY_MINI_GROUP = '1 day';
    
    public function __construct(IGroupRepository $groupRepository,
            ICurrentUser $currentUser,
            IPollRepository $pollRepository,
            IGameRepository $gameRepository)
    {
        $this->_groupRepository = $groupRepository;
        $this->_currentUser = $currentUser;
        $this->_pollRepository = $pollRepository;
        $this->_gameRepository = $gameRepository;
    }

    public function CreateGroup($name, $description, \DateTime $start, \DateTime $end)
    {
        if(empty($name))
        {
            throw new InvalidArgumentException("name", null);
        }
        
        if(empty($description))
        {
            throw new InvalidArgumentException("description", null);
        }
        
        $validator = Validator::make(
                array(
                    'name' => $name,
                    'description' => $description
                ), 
                array(
                    'name' => 'required|max:30',
                    'description' => 'required|max:255'
                ));
        
        if($validator->passes())
        {
            $exists = $this->_groupRepository->GetByName($name);
            if($exists == null || $exists->count() == 0)
            {
                $id = $this->_groupRepository->CreateGroup($name, $description, $start, $end);

                $this->JoinGroup($id);

                return $id;
            }
            else
            {
                throw new \App\Exceptions\InvalidOperationException('group name is not unique');
            }
        }
        else
        {
            throw new \App\Exceptions\InvalidOperationException($validator->messages());
        }
    }

    private function JoinGroup($idgroup)
    {
        $this->AddUserToGroup($this->_currentUser->GetId(), $idgroup);
    }
    
    public function AddUserToGroup($iduser, $idgroup)
    {
        $this->_groupRepository->PutUserInGroup($iduser, $idgroup);
        if($this->HasApplication($iduser, $idgroup))
        {
            $this->_groupRepository->DeleteApplication($iduser, $idgroup);
        }
        
        $this->GroupNotification($iduser, $idgroup, NotificationTypes::JOIN, 0);
    }

    public function QuitGroup($idgroup){
        if(!$this->IsInGroup($idgroup))
        {
            throw new \App\Exceptions\InvalidOperationException('QuitGroup');
        }
        
        $users = $this->_groupRepository->GetUsers($idgroup);
        if(count($users) > 1)
        {
            $this->GroupNotification($this->_currentUser->GetId(), $idgroup, NotificationTypes::QUIT, 0);
        }
        else
        {
            $this->_groupRepository->DeleteGroup($idgroup);
        }
        
        $this->_groupRepository->RemoveUserFromGroup($this->_currentUser->GetId(), $idgroup);
        $this->_pollRepository->DeleteUserVotes($this->_currentUser->GetId(), $idgroup);
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
                $this->GroupNotification($iduser, $idgroup, NotificationTypes::JOIN, 0);
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
        if($u == null){
            return false;
        }
        return true;
    }

    public function GroupNotification($iduser, $idgroup, $type, $poll)
    {
        $this->_groupRepository->CreateNotification($iduser, $idgroup, $type, $poll);
    }

    public function DeleteGroup($idgroup)
    {
        if($this->IsInGroup($this->_currentUser->GetId(), $idgroup))
        {
            $this->_groupRepository->DeleteGroup($idgroup);
        }
        else
        {
            throw new \App\Exceptions\InvalidOperationException("DeleteGroup");   
        }
    }

    public function ApplyForGroup($idgroup, $message)
    {
        $group = $this->_groupRepository->Get($idgroup);
        $iduser = $this->_currentUser->GetId();
        
        if($group == null)
        {
            throw new InvalidArgumentException("group doesn't exit");
        }

        if(!$this->IsInGroup($iduser, $idgroup) && !$this->HasApplication($iduser, $idgroup))
        {
            $this->_groupRepository->CreateApplication($iduser, $idgroup, $iduser, $message);
            $poll = $this->_pollRepository->CreateApplicationPoll($iduser, $idgroup);

            $this->GroupNotification($iduser, $idgroup, NotificationTypes::APPLY, $poll);
            return $poll;
        }
        else
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot apply to this group, already in.');
        }
    }
    
    public function DeleteApplication($idgroup, $idUser = 0)
    {
        $group = $this->_groupRepository->Get($idgroup);
        $userId = $idUser > 0 ? $idUser : $this->_currentUser->GetId();
        
        if($group == null)
        {
            throw new InvalidArgumentException("group doesn't exit");
        }

        if($this->HasApplication($userId, $idgroup))
        {
            $appli = $this->_groupRepository->GetApplication($userId, $idgroup);
            $this->_groupRepository->DeleteApplication(
                    $userId, 
                    $idgroup);
            
            $this->GroupNotification($userId, $idgroup, NotificationTypes::DELETE_APPLY, $appli->id_poll); 
            $this->_pollRepository->DeletePoll($appli->id_poll, \App\Models\Types\VoteTypes::NO);   
        }
        else
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot delete non existing application.');
        }
    }
    
    public function RecommandForGroup($iduser, $idgroup, $message)
    {
        $group = $this->_groupRepository->Get($idgroup);
        if($group == null)
        {
            throw new InvalidArgumentException("group doesn't exit");
        }
        
        if(!$this->IsInGroup($this->_currentUser->GetId(), $idgroup))
        {
            throw new \App\Exceptions\InvalidOperationException('Not in group');
        }

        if(!$this->IsInGroup($iduser, $idgroup) && !$this->HasApplication($iduser, $idgroup))
        {
            $this->_groupRepository->CreateApplication(
                    $iduser, 
                    $idgroup, 
                    $this->_currentUser->GetId(), 
                    $message);
            $ret = $this->_pollRepository->CreateApplicationPoll($iduser, $idgroup);
            $this->GroupNotification($iduser, $idgroup, NotificationTypes::PROPOSE, $ret);
            
            return $ret;            
        }
        else
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot recommand to this group, already in.');
        }
    }

    public function GetNotifications($idgroup)
    {
        if(!$this->IsInGroup($this->_currentUser->GetId(), $idgroup))
        {
            throw new \App\Exceptions\InvalidOperationException('Not in group');
        }
        
        return $this->_groupRepository->GetNotifications($idgroup);
    }

    public function GetApplications($idgroup)
    {
        if(!$this->IsInGroup($this->_currentUser->GetId(), $idgroup))
        {
            throw new \App\Exceptions\InvalidOperationException('Not in group');
        }
        
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

    public function GetGroupGames($id, $days)
    {
        if($days < 1)
        {
            throw new InvalidArgumentException('days', $days);
        }
        
        if(!$this->IsInGroup($this->_currentUser->GetId(), $id))
        {
            throw new \App\Exceptions\InvalidOperationException('Not in group');
        }
        
        return $this->_groupRepository->GetGroupGames($id, 7);
    }

    public function GetBetsForGroupAndGame($id, $param)
    {
        if(!$this->IsInGroup($this->_currentUser->GetId(), $id))
        {
            throw new \App\Exceptions\InvalidOperationException('Not in group');
        }
        
        return $this->_groupRepository->GetBetsForGroupAndGame($id, $param);
    }

    public function GetUsers($id_group)
    {
        return $this->_groupRepository->GetUsers($id_group);
    }
    
    public function SuggestGameForGroup($group, $game)
    {
        if(!$this->IsInGroup($this->_currentUser->GetId(), $group))
        {
            throw new \App\Exceptions\InvalidOperationException('Not in group');
        }
        
        $gameObj = $this->_gameRepository->Get($game);
        if($gameObj == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Game not found: ' . $game);
        }
        /*
        $limit = new \DateTime();
        $limit->add(\DateInterval::createFromDateString($this->DELAY_MINI_GROUP));
        $groupObj = $this->_groupRepository->Get($group);

        if($gameObj->date < $limit)
        {
            throw new \App\Exceptions\InvalidOperationException('Game is starting too soon.');
        }
        
        if($gameObj->date < $groupObj->start || $gameObj->date > $groupObj->end)
        {
            throw new \App\Exceptions\InvalidOperationException('Game is not in the interval.');
        }
        */
        if($this->_groupRepository->GroupHasGame($group, $game))
        {
            throw new \App\Exceptions\InvalidOperationException('Already added');
        }
        
        if($this->_pollRepository->GetGamePoll($group, $game) != null)
        {
            throw new \App\Exceptions\InvalidOperationException('Already voting for it');
        }
        
        $poll = $this->_pollRepository->CreateGamePoll($game, $game, $this->_currentUser->GetId());
        $this->GroupNotification($this->_currentUser->GetId(), $group, NotificationTypes::POLL_START, $poll);
        
        return $poll;
    }

    //job call
    public function AddGameToGroup($game, $group)
    {
         if($this->_gameRepository->Get($game) == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Game not found: ' . $game);
        }
        
        if($this->_groupRepository->GroupHasGame($group, $game))
        {
            throw new \App\Exceptions\InvalidOperationException('Already added');
        }
        
        $this->_groupRepository->AddGameToGroup($game, $group);
        $this->GroupNotification(null, $group, NotificationTypes::POLL_END, 0);
    }

    public function GetAll()
    {
        return $this->_groupRepository->GetAll();
    }

    /**
     * 
     * @param int $game
     * @return array
     */
    public function GetGroupsStatus($game)
    {
        if($this->_currentUser->GetGroups()->count() > 0)
        {
            $groups = $this->_currentUser->GetGroups();
            $ret = array();
            foreach ($groups as $g)
            {
                $ret[$g->id] = $this->GetGroupGameStatus($game, $g->id);
            }
            
            return $ret;
        }
        
        return array();
    }
    
    /**
     * 
     * @param int $game
     * @param int $group
     * @return \App\Models\Types\GroupGameStates
     */
    public function GetGroupGameStatus(int $game, int $group)
    {
        if($this->_groupRepository->GroupHasGame($group, $game))
        {
            return \App\Models\Types\GroupGameStates::IN_GROUP;
        }
        
        if($this->_pollRepository->GetGamePoll($group, $game) != null)
        {
            return \App\Models\Types\GroupGameStates::IN_VOTE;
        }
        
        return \App\Models\Types\GroupGameStates::NOTHING;
    }

}