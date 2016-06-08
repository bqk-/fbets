<?php namespace App\Models\ViewModels;

class IndexViewModel
{
    public $Games;
    
    public function __construct(GameViewModel ...$games)
    {
        $this->Games = $games;
    }
}