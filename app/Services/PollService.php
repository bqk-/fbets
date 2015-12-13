<?php namespace App\Services;
use App\Models\Data\Poll;
use App\Models\Types\PollTypes;
use App\Models\Data\User;
use App\Models\Data\Vote;
use App\Models\VoteTypes;
use \Auth;
use League\Flysystem\Exception;

/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 17/06/15
 * Time: 20:44
 */

define('EXPIRATION_DELAY', new \DateInterval("7 days"));

class PollService
{
    private $_pollRepository;
    
    public function __construct(\App\Repositories\Contracts\IPollRepository $pollRespository) 
    {
        $this->_pollRepository = $pollRespository;
    }
    
    public function CreatePoll($id_group, $id, $type)
    {
        $this->_pollRepository->CreatePoll(Auth::user()->id, $id_group, $id, $type);
    }

    public function AddVote($id_poll, $opinion)
    {
        $this->_pollRepository->AddVote(Auth::user()->id, $id_poll, $opinion);
    }

    /*
     * Call this function from Cron/Update, whatever is updating the stuff
     */
    public function UpdatePolls()
    {
        $this->_pollRepository->UpdatePolls();
    }
}