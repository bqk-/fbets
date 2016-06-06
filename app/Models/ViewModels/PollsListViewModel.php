<?php namespace App\Models\ViewModels;

use App\Models\Data\Group;

class PollsListViewModel
{
    public $ActivesPolls = array();
    public $ExpiredPolls = array();
    public $Group;
    
    public function __construct(Group $group, array $actives, array $expired)
    {
        $this->ActivesPolls = $actives;
        $this->ExpiredPolls = $expired;
        $this->Group = $group;
    }
}