<?php namespace App\Services;

use App\Repositories\Contracts\IGameRepository;
use App\Repositories\Contracts\IScoreRepository;
use App\Repositories\Contracts\ISportRepository;
use Exception;
use \Auth;

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

    public function __construct(IGameRepository $gameRepository, 
            IScoreRepository $scoreRepository,
            ISportRepository $sportRespository,
            \App\Services\Contracts\ICurrentUser $currentUser)
    {
        $this->_gameRepository = $gameRepository;
        $this->_scoreRepository = $scoreRepository;
        $this->_sportRepository = $sportRespository;
        $this->CurrentUser = $currentUser;
    }

    public function Get($id)
    {
        return $this->_gameRepository->Get($id);
    }

    public function GetScore($id)
    {
        return $this->_scoreRepository->GetForGame($id);
    }

    public function AddScore($idGame, $score1, $score2)
    {
        $game = $this->_gameRepository->Get($idGame);
        $this->_scoreRepository->AddScore($game->Id, $score1, $score2);

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

    public function Create($game, $idChamp, $teamsId)
    {
        $g = new Game();
        $g->id_championship = $idChamp;
        $g->team1 = $teamsId[$game->team1];
        $g->team2 = $teamsId[$game->team2];
        $g->date = $game->date;
        $g->save();
        return $g->id;
    }

    public function CreateFromPost($team1, $team2, $event, $date, $time)
    {
        $g = new Game;
        $g->team1 = $team1;
        $g->team2 = $team2;
        $g->id_championship = $event;
        $g->date = $date.' '.$time.':00';
        $g->save();
        return $g->id;
    }

    public function DropGamesForChampionship($id)
    {
        return $this->_gameRepository->DropGamesForChampionship($id);
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

}