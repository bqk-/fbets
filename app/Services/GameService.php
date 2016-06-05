<?php namespace App\Services;

use App\Repositories\Contracts\IGameRepository;
use App\Repositories\Contracts\IScoreRepository;
use App\Repositories\Contracts\ISportRepository;
use App\Repositories\Contracts\ITeamRepository;
use App\Repositories\Contracts\IBetRepository;
use Exception;

/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 29/07/15
 * Time: 12:35
 */

class GameService
{
    private $_gameRepository;
    private $_scoreRepository;
    private $_sportRepository;
    private $CurrentUser;
    private $_teamRepository;
    private $_betRepository;

    public function __construct(IGameRepository $gameRepository, 
            IScoreRepository $scoreRepository,
            IBetRepository $betRepository,
            ISportRepository $sportRespository,
            ITeamRepository $teamRepository,
            \App\Services\Contracts\ICurrentUser $currentUser)
    {
        $this->_gameRepository = $gameRepository;
        $this->_scoreRepository = $scoreRepository;
        $this->_sportRepository = $sportRespository;
        $this->_teamRepository = $teamRepository;
        $this->CurrentUser = $currentUser;
        $this->_betRepository = $betRepository;
    }

    public function Get($id)
    {
        return $this->_gameRepository->Get($id);
    }

    public function GetScore($id)
    {
        return $this->_scoreRepository->GetForGame($id);
    }

    public function AddScore($idGame, $score1, $score2, $state)
    {
        $game = $this->_gameRepository->Get($idGame);
        if($game == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot add score to unknow game');
        }
        
        $this->_scoreRepository->AddScore($game->Id, $score1, $score2, $state);

        $this->UpdateBetsForGame($game, $s);
    }

    public function UpdateGameTime($idGame, $time)
    {
        if($time == '' || $time == null)
        {
            throw new Exception('Feed me with a time or fail');
        }

        $game = $this->Get($idGame);
        $game->date = $time;

        $game->save();
    }

    public function Create(\App\Models\Admin\TournamentClasses\Game $game, $idChamp, $relations)
    {
        $id = $this->_gameRepository->Create($relations[$game->TeamHome->Id], 
                $relations[$game->TeamVisit->Id],
                $idChamp,
                $game->Date);
        
        $this->_gameRepository->CreateRelation($game->Id, $id);
        
        if($game->Score != null)
        {
            $this->_scoreRepository->AddScore($id,
                    $game->Score->TeamHome, 
                    $game->Score->TeamVisit, 
                    $game->Score->State);
        }
        
        return $id;
    }

    public function GetNext7DaysGames()
    {
        return $this->_gameRepository->GetNext7DaysGameAllSport();
    }

    public function GetUserSuggestions() 
    {
        return $this->_gameRepository->GetUserSuggestions($this->CurrentUser->GetId());
    }

    public function Suggest($sport, $team1, $team2, $event, $date) 
    {
        $data = array(
                'sport' => $sport,
                'team1' => $team1,
                'team2' => $team2,
                'event' => $event,
                'date' => $date
            );
        $validator = Validator::make(
            $data,
            array(
                'team1' => array('required', 'min:3', 'alpha_spaces'),
                'team2' => array('required', 'min:3', 'alpha_spaces'),
                'date' => array('required', 'date_format:"Y-m-d H:i:s"'),
                'sport' => array('required'),
            )
        );
        
        if(!$validator->passes() && $this->_sportRepository->Get($sport) !== null)
        {
            throw new App\Exceptions\InvalidArgumentException('validator', $data);
        }
        
        $this->_gameRepository->Suggest($sport, $team1, 
                $team2, $event, $date, 
                $this->CurrentUser == null ? null : $this->CurrentUser->GetId());
    }

    public function GetGamesWithNoScore($champId)
    {
        return $this->_gameRepository->GetGamesWithNoScore($champId);
    }
    
    public function GetAllGames($idChamp)
    {
        return $this->_gameRepository->GetAllGames($idChamp);
    }

    public function SaveRates($idGame, $rHome, $rDraw, $rVisit)
    {
        return $this->_gameRepository->SaveRates($idGame, $rHome, $rDraw, $rVisit);
    }

}