<?php namespace App\Models\Admin\Tournament;

use App\Models\Admin\TournamentClasses\Team;
use App\Models\Admin\TournamentClasses\Game;
use App\Models\Admin\TournamentClasses\Score;
use GuzzleHttp\Client;

class Euro2016 implements ITournament
{
    private $rootUrl = 'http://api.football-data.org/v1/soccerseasons/%d';
    private $teamsUrl = 'http://api.football-data.org/v1/soccerseasons/%d/teams';
    private $gamesUrl = 'http://api.football-data.org/v1/soccerseasons/%d/fixtures';
    
    private $client = null;
    
    private $games = array();
    private $teams = array();
    
    public function __construct($id)
    {
        $this->rootUrl = sprintf($this->rootUrl, $id);
        $this->teamsUrl = sprintf($this->teamsUrl, $id);
        $this->gamesUrl = sprintf($this->gamesUrl, $id);
    }
    
    private function DoRequest($uri)
    {
        if($this->client === null)
        {
            $this->client = new Client();
        }
        
        $header = array('headers' => array('X-Auth-Token' => '69336ef99a65498898bac719095095ea'));
        $response = $this->client->get($uri, $header);          
        $json = json_decode($response->getBody());
        
        return $json;
    }
    
    public function getGameStateFromScore($scoreH, $scoreV)
    {
        if(is_int($scoreH) && is_int($scoreV))
        {
            if($scoreH > $scoreV)
            {
                return \App\Models\Types\GameStates::HOME;
            }
            elseif ($scoreH < $scoreV)
            {
                return \App\Models\Types\GameStates::VISITOR;
            }
            else
            {
                return \App\Models\Types\GameStates::DRAW;
            }
        }
        
        return \App\Models\Types\GameStates::NONE;
    }

    public function getGameTime($extIdGame)
    {
        $this->getGames();
        if(key_exists($extIdGame, $this->games))
        {
            return $this->games[$extIdGame]->Date;
        }
        
        return null;
    }

    public function getGames()
    {
        $this->getTeams();
        if(empty($this->games))
        {
            $result = $this->DoRequest($this->gamesUrl);
            foreach ($result->fixtures as $t)
            {
                $date = new \DateTime($t->date);
                $date->add(date_interval_create_from_date_string('2 hours'));
                $score = $t->result->goalsHomeTeam == null || $t->result->goalsAwayTeam == null ?
                        null : new Score($t->result->goalsHomeTeam,
                                  $t->result->goalsAwayTeam,
                                  $this->getGameStateFromScore($t->result->goalsHomeTeam,
                                                               $t->result->goalsAwayTeam));
                $this->games[md5($t->_links->self->href)] = new Game(md5($t->_links->self->href),
                        $this->teams[md5($t->_links->homeTeam->href)],
                        $this->teams[md5($t->_links->awayTeam->href)],
                        $date->format('Y-m-d G:i:s'),
                        $score);
            }
        }
        
        return $this->games;
    }

    public function getScore($extIdGame)
    {
        $this->getGames();
        if(key_exists($extIdGame, $this->games))
        {
            return $this->games[$extIdGame]->Score;
        }
        
        return null;
    }

    public function getTeams()
    {
        if(empty($this->teams))
        {
            $result = $this->DoRequest($this->teamsUrl);
            foreach ($result->teams as $t)
            {
                $this->teams[md5($t->_links->self->href)] = new Team(md5($t->_links->self->href), $t->name, $t->crestUrl);
            }
        }
        
        return $this->teams;
    }

    public function getType()
    {
        return \App\Models\Admin\iTournamentType::CUP;
    }
}