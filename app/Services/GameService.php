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

    /**
    * @return \App\Models\Services\BetRates
    */
    public function GetRates($gameId)
    {
        $percent = $this->GetPercent($gameId);
        if($percent->HomeRate > 0.8)
        {
            $percent->VisitRate += ($percent->HomeRate - 0.8) / 2;
            $percent->DrawRate += ($percent->HomeRate - 0.8) / 2;
        }
        
        if($percent->VisitRate > 0.8)
        {
            $percent->HomeRate += ($percent->VisitRate - 0.8) / 2;
            $percent->DrawRate += ($percent->VisitRate - 0.8) / 2;
        }
        
        if($percent->DrawRate > 0.8)
        {
            $percent->VisitRate += ($percent->DrawRate - 0.8) / 2;
            $percent->HomeRate += ($percent->DrawRate - 0.8) / 2;
        }

        if($percent->HomeRate < 0.1)
        {
            $percent->VisitRate -= (0.1 - $percent->HomeRate) / 2;
            $percent->DrawRate -= (0.1 - $percent->HomeRate) / 2;
        }
        
        if($percent->VisitRate < 0.1)
        {
            $percent->HomeRate -= (0.1 - $percent->VisitRate) / 2;
            $percent->DrawRate -= (0.1 - $percent->VisitRate) / 2;
        }
        
        if($percent->DrawRate < 0.1)
        {
            $percent->VisitRate -= (0.1 - $percent->DrawRate) / 2;
            $percent->HomeRate -= (0.1 - $percent->DrawRate) / 2;
        }
        
        return $percent;
    }
    
    /**
    * @return \App\Models\Services\BetRates
    */
    public function GetPercent($gameId)
    {
        $bets = $this->_betRepository->GetBetsOnGame($gameId);
        $total = 0;
        $home = 0;
        $visit = 0;
        $draw = 0;
        
        if($bets->count() == 0)
        {
            return new \App\Models\Services\BetRates($gameId, 0.33, 0.33, 0.33);
        }
        
        foreach ($bets as $bet)
        {
            if($bet->state == \App\Models\Types\GameStates::HOME)
            {
                $home++;
            }
            else if($bet->state == \App\Models\Types\GameStates::VISITOR)
            {
                $visit++;
            }
            else if($bet->state == \App\Models\Types\GameStates::DRAW)
            {
                $draw++;
            }
            else
            {
                throw new \App\Exceptions\InvalidOperationException('What the fuck is this bet');
            }
            
            $total++;
        }
        
        return new \App\Models\Services\BetRates($gameId, $home/$total, $visit/$total, $draw/$total);
    }
}