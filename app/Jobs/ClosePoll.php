<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Services\PollService;
use App\Services\GroupService;

class ClosePoll extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    private $PollService;
    private $GroupService;
    
    private $IdPoll;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id_poll)
    {
        $this->IdPoll = $id_poll;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PollService $pollService,
                GroupService $groupService)
    {
        $this->PollService = $pollService;
        $this->GroupService = $groupService;
        
        $poll = $this->PollService->Get($this->IdPoll);
        if($poll == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Poll undefined, please fix me: ' . $this->IdPoll);
        }
        
        $totalUsers = $this->GroupService->GetUsers($poll->id_group)->count();
        $votes = $this->PollService->GetVotes($poll->id);
        if(!$this->PollService->IsExpired($poll->created_at)
                && $votes->count() < $totalUsers)
        {
            throw new \App\Exceptions\InvalidOperationException('Now is not the time to deal with me');
        }
        
        $this->EndPoll($poll, $votes, $totalUsers);
    }
    
    private function EndPoll(\App\Models\Data\Poll $poll, $votes, $totalUsers)
    {
        $majority = floor($totalUsers / 2) + 1;
        $yes = 0;
        $no = 0;

        foreach($votes as $vote)
        {
            if($vote->opinion == \App\Models\Types\VoteTypes::YES)
            {
                $yes++;
            }
            else if($vote->opinion == \App\Models\Types\VoteTypes::NO)
            {
                $no++;
            }
        }
        
        if($yes > $no && $yes >= $majority)
        {
            $this->AcceptPoll($poll);
        }
        else
        {
            $this->RefusePoll($poll);
        }
    }

    private function AcceptPoll($poll)
    {
        switch($poll->type)
        {
            case \App\Models\Types\PollTypes::USER_ADD:
                $this->GroupService->AddUserToGroup($poll->id_user, $poll->id_group);
                break;
            
            case \App\Models\Types\PollTypes::GAME_ADD:
                $this->GroupService->AddGameToGroup($poll->id_game, $poll->id_group);
                break;
            
            //TODO: Code other types
            default:
                throw new \App\Exceptions\InvalidOperationException("Not implemented");
        }

        $this->PollService->DeletePoll($poll->id, \App\Models\Types\VoteTypes::YES);
    }
    
    private function RefusePoll($poll)
    {
        switch($poll->type)
        {
            case \App\Models\Types\PollTypes::USER_ADD:
                $this->GroupService->DeleteApplication($poll->id_group, $poll->id_user);
                break;
            
            case \App\Models\Types\PollTypes::GAME_ADD:
                $this->PollService->DeletePoll($poll->id, \App\Models\Types\VoteTypes::NO);
                break;
            
            //TODO: Code other types
            default:
                throw new \App\Exceptions\InvalidOperationException("Not implemented");
        }
    }
}
