<?php namespace App\Repositories;

use App\Models\Data\User;
use App\Models\Data\Group;
use App\Models\Data\Game;
use App\Models\Data\GroupNotification;
use App\Models\Data\GroupApplication;
use App\Repositories\Contracts\IGroupRepository;

class GroupRepository implements IGroupRepository
{
    public function __construct()
    {
    }
    
    /**
    * Returns group from id
    *
    * @param int $id id of the group
    * @return App\Models\Data\Group the group
    */
    public function Get($id)
    {
        $group = Group::find($id);
        if($group == null)
        {
            throw new \App\Exceptions\NotFoundException('group', 'id', $id);
        }

        return $group;
    }
    
    public function GetUsers($idgroup)
    {
        return $this->Get($idgroup)->users();
    }
    
    public function CreateGroup($name, $description, \DateTime $start, \DateTime $end) 
    {
        $g = new Group;
        $g->name = $name;
        $g->description = $description;
        $g->start = $start;
        $g->end = $end;
        $g->money = 0;
        $g->save();
        
        return $g->id;
    }
    
    public function PutUserInGroup($user, $group)
    {
        $u = User::find($user);
        $u->groups()->attach($group);
    }
    
    public function RemoveUserFromGroup($user, $group)
    {
        $u = User::find($user);
        $u->groups()->detach($group);
        $u->save();
    }
    
    public function DeleteGroup($idgroup)
    {
        $g = Group::find($idgroup);
        $g->users()->detach();
        GroupApplication::where('id_group', '=', $g->id)->delete();
        GroupNotification::where('id_group', '=', $g->id)->delete();
        GroupPoll::where('id_group', '=', $g->id)->delete();
        $g->delete();
    }
    
    public function GetApplication($iduser, $idgroup)
    {
        $a = GroupApplication::
                where('id_user', '=', $iduser)
                ->where('from', '=', $iduser)
                ->where('id_group', '=', $idgroup)
                ->first();
        return $a;
    }
    
    public function DeleteApplication($iduser, $idgroup)
    {
        $a = GroupApplication::
                where('id_user', '=', $iduser)
                ->where('from', '=', $iduser)
                ->where('id_group', '=', $idgroup)
                ->first();
        $a->delete();
    }
    
    public function IsInGroup($iduser, $idgroup)
    {
        $u = User::find($iduser)->groups()->where('id', '=', $idgroup)->first();
        return $u != null;
    }
    
    public function CreateNotification($iduser, $idgroup, $type, $poll)
    {
        $n = new GroupNotification;
        $n->id_group = $idgroup;
        $n->id_user = $iduser;
        $n->type = $type;
        $n->date = date('Y-m-d G:i:s');
        $n->id_poll = $poll;
        $n->save();
        return $n->id;
    }
    
    public function CreateApplication($iduser, $idgroup, $from, $message)
    {
        $n = new GroupApplication;
        $n->id_group = $idgroup;
        $n->id_user = $iduser;
        $n->from = $from;
        $n->message = $message;
        $n->save();
        
        return $n->id;
    }
       
    public function GetNotifications($idgroup, $limit = 20)
    {
        return GroupNotification::
            where('id_group', '=', $idgroup)
            ->join('users as u', 'u.id', '=', 'groups_notifications.id_user')
            ->select('id_group', 'type', 'date', 'u.pseudo')
            ->orderBy('date', 'desc')
            ->take($limit)
            ->get();
    }
    
    public function GetApplications($idgroup, $limit = 20)
    {
        return GroupApplication::
            where('id_group', '=', $idgroup)
            ->join('users as u', 'u.id', '=', 'groups_requests.id_user')
            ->join('users as u2', 'u2.id', '=', 'groups_requests.from')
            ->select('message', 'groups_requests.created_at', 'u.pseudo', 'u2.pseudo as from')
            ->get();
    }

    public function GetByName($name) 
    {
        return Group::where('name', '=', $name)->get();
    }

    public function GetGroupGames($id, $days)
    {
        $group = $this->Get($id);
        return $group->games()->get();
    }
    
    public function GetBetsForGroupAndGame($idgroup, $idgame)
    {
        $group = $this->Get($idgroup);
        return $group->games()->where('id', '=', $idgame)->first();
    }

    public function AddGameToGroup($game, $group)
    {
        $groupObj = $this->Get($group);
        $gameObj = Game::find($game);
        if($game == null)
        {
            throw new \App\Exceptions\NotFoundException('Game not found:' . $game);
        }
        
        $groupObj->games()->attach($gameObj);
    }

    public function GroupHasGame($group, $game)
    {
        $groupObj = $this->Get($group);
        
        foreach($groupObj->games()->get() as $g)
        {
            if($g->id == $game)
            {
                return true;
            }
        }
        
        return false;
    }

    public function GetAll()
    {
        return Group::all();
    }

}

