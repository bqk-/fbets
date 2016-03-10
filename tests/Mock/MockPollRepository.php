<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockPollRepository implements \App\Repositories\Contracts\IPollRepository
{
    private $seed = 1;
    private $polls = array();
    private $votes = array();
    
    public function AddVote($userid, $id_poll, $opinion) {
        $v = new \App\Models\Data\Vote;
        $v->id_user = $userid;
        $v->id_poll = $id_poll;
        $v->opinion = $opinion;
        
        $this->votes[$id_poll][] = $v;
    }

    private function CreatePoll($user, $id_group, $id_game, $type) 
    {
        $a = new \App\Models\Data\Poll();
        $a->id_user = $user;
        $a->id_game = 0;
        $a->id_group = $id_group;
        $a->type = $type;
        $a->id = ++$this->seed;
        $this->polls[$id_group][] = $a;
        
        return $this->seed;
    }
    
    public function CreateApplicationPoll($iduser, $idgroup) 
    {
        return $this->CreatePoll($iduser, $idgroup, 0, \App\Models\Types\PollTypes::USER_ADD);
    }
    
    public function CreateGamePoll($idgame, $idgroup) 
    {
        return $this->CreatePoll(0, $idgroup, $idgame, \App\Models\Types\PollTypes::GAME_ADD);
    }

    public function GetVotes($id_poll)
    {
        if(key_exists($id_poll, $this->votes))
        {
            return new \Illuminate\Database\Eloquent\Collection($this->votes[$id_poll]);
        }
        
        return null;
    }

    public function DeleteUserVotes($id_user, $idgroup)
    {
        //
    }

    public function GetPoll($id_poll)
    {
        foreach ($this->polls as $group)
        {
            foreach ($group as $poll)
            {
                if($poll->id == $id_poll)
                {
                    return $poll;
                }
            } 
        }
        
        return null;
    }

    public function GetVote($id_poll, $id_user)
    {
        $votes = $this->GetVotes($id_poll);
        
        if($votes == null)
        {
            return null;
        }
                
        return $votes->where('id_user', $id_user)->first();
    }

    public function GetPollsCreatedBefore($date)
    {
        
    }

    public function DeletePoll($id_poll)
    {
        foreach ($this->polls as $group)
        {
            foreach ($group as $key => $poll)
            {
                if($poll->id == $id_poll)
                {
                    unset($group[$key]);
                }
            } 
        }
    }

}