<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 14/06/15
 * Time: 18:31
 */

namespace App\Models\Admin;

use App\Models\Data\Game;
use App\Models\Data\Team;

class LeagueOfLegends implements ITournament {

    private $url = "http://na.lolesports.com:80/api/schedule.json?tournamentId=%d&includeFinished=true&includeFuture=true&includeLive=true
";

    private $scores = array();
    private $games = array();
    private $teams = array();

    public function __construct($id)
    {
        $response = file_get_contents(sprintf($this->url, $id));
        $results = json_decode($response);
        foreach ($results as $game)
        {
            $idblue =  $game->contestants->blue->id;
            $idred =  $game->contestants->red->id;

            $g = new Game;
            $g->team1 = $idblue;
            $g->team2 = $idred;
            $g->logo1 = $game->contestants->blue->logoURL;
            $g->logo2 = $game->contestants->red->logoURL;
            $date = new \DateTime($game->dateTime);
            $date->add(date_interval_create_from_date_string('2 hours'));
            $g->date = $date->format('Y-m-d G:i:s');

            if(property_exists($game,'winnerId') && $game->winnerId != ""){
                if($game->winnerId == $idblue){
                    $this->scores[$idblue.'-'.$idred] = "1-0";
                }
                else{
                    $this->scores[$idblue.'-'.$idred] = "0-1";
                }
            }

            if(!array_key_exists($idblue, $this->teams))
            {
                $t = new Team;
                $t->name = $game->contestants->blue->name;
                $t->logo = $game->contestants->blue->logoURL;
                $t->id = $idblue;
                $this->teams[$idblue] = $t;
            }

            if(!array_key_exists($idred, $this->teams))
            {
                $t = new Team;
                $t->name = $game->contestants->red->name;
                $t->logo = $game->contestants->red->logoURL;
                $t->id = $idred;
                $this->teams[$idred] = $t;
            }

            $this->games[$idblue.'-'.$idred] = $g;
        }
    }

    public function getGames()
    {
        return $this->games;
    }

    public function getGameTime($team1, $team2)
    {
        if(!empty($this->games[$team1.'-'.$team2]))
        {
            return $this->games[$team1.'-'.$team2]->date;
        }

        return null;
    }

    public function getScore($team1, $team2)
    {
        if(!empty($this->scores[$team1.'-'.$team2]))
        {
            $s = explode('-',$this->scores[$team1.'-'.$team2]);
            if($s[0] != '' && $s[1] != '')
            {
                return $this->scores[$team1.'-'.$team2];
            }
        }

        return null;
    }

    function getTeams()
    {
        return $this->teams;
    }
}