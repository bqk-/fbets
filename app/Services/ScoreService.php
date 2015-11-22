<?php namespace App\Services;

use App\Models\Data\Score;
use App\Repositories\Contracts\IScoreRepository;

class ScoreService
{
    private $_scoreRepository;

    public function __construct(IScoreRepository $scoreRepository)
    {
        $this->_scoreRepository = $scoreRepository;
    }

    public function Create($idGame, $score1, $score2)
    {
        $this->_scoreRepository->AddScore($idGame, $score1, $score2);
    }
}