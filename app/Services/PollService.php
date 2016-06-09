<?php namespace App\Services;

use App\Repositories\Contracts\IPollRepository;
use App\Repositories\Contracts\IGroupRepository;
use App\Services\Contracts\ICurrentUser;
use App\Jobs\ClosePoll;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Services\PollStatus;

define('EXPIRATION_DELAY', "7 days");

class PollService
{
    use DispatchesJobs;
    
    private $_pollRepository;
    private $_currentUser;
    private $_groupRepository;
    
    public function __construct(IPollRepository $pollRespository,
        ICurrentUser $currentUser,
        IGroupRepository $groupRepository) 
    {
        $this->_pollRepository = $pollRespository;
        $this->_currentUser = $currentUser;
        $this->_groupRepository = $groupRepository;
    }

    public function AddVote($id_poll, $opinion)
    {
        $poll = $this->_pollRepository->GetPoll($id_poll);
        if($poll == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Vote on non existing poll');
        }
        
        $userVote = $this->_pollRepository->GetVote($id_poll, $this->_currentUser->GetId());
        if($userVote != null)
        {
            throw new \App\Exceptions\InvalidOperationException('A vote exists already');
        }
        
        $this->_pollRepository->AddVote($this->_currentUser->GetId(), $poll->id, $opinion);

        $users = $this->_groupRepository->GetUsers($poll->id_group);
        if($this->GetVotes($id_poll)->count() >= $users->count())
        {
            if(env('APP_ENV') != 'testing')
            {
                Queue::push(new ClosePoll($poll->id));
            }
        }
    }
    
    public function GetVotes($id_poll)
    {
        return $this->_pollRepository->GetVotes($id_poll);
    }

    public function Get($id_poll)
    {
        return $this->_pollRepository->GetPoll($id_poll);
    }

    public function IsExpired($date_poll)
    {
        $date = new \DateTime();
        $date->sub(\DateInterval::createFromDateString(EXPIRATION_DELAY));
        
        if($date_poll < $date)
        {
            return true;
        }
        
        return false;
    }
    
    public function GetExpiredPollsForGroup($group)
    {
        return $this->_pollRepository->GetPollsPastForGroup($group);
    }

    public function DeletePoll($id_poll, $status)
    {
        return $this->_pollRepository->DeletePoll($id_poll, $status);
    }

    public function GetActivePollsForGroup($group)
    {
        return $this->_pollRepository->GetPollsActiveForGroup($group);
    }

    /**
     * @return \App\Models\Services\PollStatus
     */
    public function GetPercents($id_poll)
    {
        $poll = $this->_pollRepository->GetPollWithDeleted($id_poll);
        if($poll == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Poll undefined, please fix me: ' . $this->IdPoll);
        }
        
        $votes = $this->_pollRepository->GetVotes($id_poll);
        $nbUsers = $this->_groupRepository->GetUsers($poll->id_group)->count();
        
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
        
        return new PollStatus($yes, $no, $nbUsers - $yes - $no);
    }

    public function GetUserVote($id_poll)
    {
        $vote = $this->_pollRepository->GetVote($id_poll, $this->_currentUser->GetId());
        if($vote == null)
        {
            return \App\Models\Types\VoteTypes::DONTCARE;
        }
        
        if($vote->opinion == \App\Models\Types\VoteTypes::YES)
        {
            return \App\Models\Types\VoteTypes::YES;
        }
        
        if($vote->opinion == \App\Models\Types\VoteTypes::NO)
        {
            return \App\Models\Types\VoteTypes::NO;
        }
    }

}