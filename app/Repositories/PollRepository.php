<?php namespace App\Repositories;

use App\Models\Data\Poll;
use App\Models\Types\PollTypes;
use App\Models\Data\Vote;
use App\Repositories\Contracts\IPollRepository;

class PollRepository implements IPollRepository
{
    public function CreatePoll($userid, $id_group, $id, $type)
    {
        $poll = new Poll;
        if($type == PollTypes::GAME){
            $poll->id_user = $userid;
            $poll->id_game = $id;
        }
        else if($type == PollTypes::USER){
            $poll->id_user = $id;
            $poll->id_game = 0;
        }
        else{
            throw new Exception;
        }
        $poll->status = 1;
        $poll->id_group = $id_group;
        $poll->save();
    }

    public function AddVote($userid, $id_poll, $opinion)
    {
        $poll = Poll::find($id_poll);
        if($poll != null){
            $vote = new Vote();
            $vote->id_user = $userid;
            $vote->id_poll = $poll->id;
            $vote->opinion = $opinion;
            $vote->save();
        }
        else{
            throw new Exception("Can't vote for an non-existent poll.");
        }
    }

    private function EndPoll($id)
    {
        $poll = Poll::find($id);
        if($poll != null){
            $votes = $poll->votes();
            $poll->status = 1;
            $poll->save();
            $yes = 0;
            $no = 0;
            $total = count($votes);
            foreach($votes as $vote){
                if($vote->opinion == VoteTypes::YES){
                    $yes++;
                }
                else if($vote->opinion == VoteTypes::NO){
                    $no++;
                }
            }
            if($yes > $no){
                $this->AcceptPoll($id);
            }
            else{
                $this->ClosePoll($id);
            }
        }
        else{
            throw new Exception("Can't vote for an non-existent poll.");
        }
    }

    private function ClosePoll($id)
    {
        $poll = Poll::find($id);
        if($poll != null){
            $poll->status = 2;
            $poll->save();
        }
        else{
            throw new Exception("Can't vote for an non-existent poll.");
        }
    }

    private function AcceptPoll($id){
        $poll = Poll::find($id);
        if($poll != null){
            switch($poll->type){
                case PollTypes::USER:
                    $user = User::find($poll->id_user);
                    $user->groups()->attach($poll->id_group);
                    $user->save();
                    break;

                case PollTypes::GAME:
                    //TODO: accept game
                    break;

                default:
                    throw new Exception("Not implemented");
                    break;
            }
            $this->ClosePoll($id);
        }
        else{
            throw new Exception("Can't vote for an non-existent poll.");
        }
    }

    private function GetExpiredPoll()
    {
        $date = new \DateTime();
        $date->add(EXPIRATION_DELAY);
        $polls = Poll::where('created_at' , '<', $date);
        return $polls;
    }
    
    public function UpdatePolls() 
    {
        foreach($this->GetExpiredPoll() as $poll)
        {
            $this->EndPoll($poll->id);
        }
    }
}

