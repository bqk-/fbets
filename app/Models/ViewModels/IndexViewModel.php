<?php namespace App\Models\ViewModels;

class IndexViewModel
{
    public $Games;
    
    public function __construct(array $games)
    {
        $this->Games = new \Illuminate\Support\Collection($games);
    }
}