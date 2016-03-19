<?php namespace App\Repositories;

use App\Models\Data\Poll;
use App\Models\Types\PollTypes;
use App\Models\Data\Vote;
use App\Repositories\Contracts\IPollRepository;

class PollRepository implements IPollRepository
{
    private function CreatePoll($userid, $id_group, $idgame, $type)
    {
        $poll = new Poll;
        $poll->id_user = $userid;
        $poll->id_game = $idgame;
        $poll->status = 0;
        $poll->id_group = $id_group;
        $poll->save();
    }

    public function AddVote($userid, $id_poll, $opinion)
    {
        $poll = Poll::find($id_poll);
        if($poll != null)
        {
            $vote = new Vote();
            $vote->id_user = $userid;
            $vote->id_poll = $poll->id;
            $vote->opinion = $opinion;
            $vote->save();
        }
        else
        {
            throw new \App\Exceptions\InvalidOperationException("Can't vote for an non-existent poll.");
        }
    }

    public function GetPollsCreatedBefore($date)
    {
        $polls = Poll::where('created_at' , '<', $date);
        return $polls;
    }
    
    public function CreateApplicationPoll($iduser, $idgroup)
    {
        return $this->CreatePoll($iduser, $idgroup, 0, PollTypes::USER_ADD);
    }
       
    public function CreateGamePoll($idgame, $idgroup) 
    {
        return $this->CreatePoll(0, $idgroup, $idgame, \App\Models\Types\PollTypes::GAME_ADD);
    }

    public function DeleteUserVotes($id_user, $idgroup)
    {
        Vote::where('id_user', $id_user)->where('id_group', $idgroup)->delete();
    }

    public function GetPoll($id_poll)
    {
        $p = Poll::find($id_poll);
        if($p == null)
        {
            throw new \App\Exceptions\InvalidOperationException('no poll with id: ' . $id_poll);
        }
        
        return $p;
    }

    public function GetVote($id_poll, $id_user)
    {
        return Vote::where('id_user', $id_user)->where('id_poll', $id_poll)->first();
    }

    public function GetVotes($id_poll)
    {
        return Vote::where('id_poll', $id_poll)->get();
    }

    public function DeletePoll($id_poll)
    {
        Vote::find($id_poll)->delete();
    }

    public function GetGamePoll($group, $game)
    {
        return Poll::where('id_game', $game)->get();
    }

}

