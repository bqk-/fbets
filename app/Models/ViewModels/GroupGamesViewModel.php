<?php namespace App\Models\ViewModels;

use App\Models\Data\Group;

class GroupGamesViewModel
{
    public $Group;
    public $Games;
    
    public function __construct(Group $group, array $games)
    {
        $this->Games = new \Illuminate\Support\Collection($games);
        $this->Group = $group;
    }
}

    