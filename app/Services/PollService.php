<?php namespace App\Services;

use App\Repositories\Contracts\IPollRepository;
use App\Repositories\Contracts\IGroupRepository;
use App\Services\Contracts\ICurrentUser;
use App\Jobs\ClosePoll;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Bus\DispatchesJobs;

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
        if(($poll->type == \App\Models\Types\PollTypes::USER_ADD || $poll->type == \App\Models\Types\PollTypes::USER_DEL)
                && $this->GetVotes($id_poll)->count() >= $users->count())
        {
            Queue::push(new ClosePoll($poll->id));
        }
    }
    
    public function GetVotes($id_poll)
    {
        return $this->_pollRepository->GetVotes($id_poll);
    }

    /*
     * Call this function from Cron/Update, whatever is updating the stuff
     */
    public function UpdatePolls()
    {
        $this->_pollRepository->UpdatePolls();
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

    public function GetExpiredPolls()
    {
        $date = new \DateTime();
        $date->sub(\DateInterval::createFromDateString(EXPIRATION_DELAY));
        
        return $this->_pollRepository->GetPollsCreatedBefore($date);
    }

    public function DeletePoll($id_poll)
    {
        return $this->_pollRepository->DeletePoll($id_poll);
    }

}