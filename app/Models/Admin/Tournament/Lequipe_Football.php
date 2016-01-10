<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 28/07/15
 * Time: 21:51
 */

namespace App\Models\Admin\Tournament;

use App\Models\Admin\TournamentClasses\Game;
use App\Models\Admin\TournamentClasses\Team;
use App\Models\Admin\TournamentClasses\Score;

class Lequipe_Football implements iTournament {
    private $games = array();
    private $teams = array();
    private $end;
    private $beg;
    private $nb_journees;

    public function __construct($start, $end){
        $this->end = preg_replace("/[^0-9]/","",$end);
        $this->beg = preg_replace("/[^0-9]/","",$start);

        $this->nb_journees = $this->end-$this->beg+1;

        for($i=1;$i<=$this->nb_journees;$i++){
            $subject = str_replace($this->beg, $this->beg+$i-1, $start);
            $lines = file($subject);
            $content = '';
            // Grab the cool line
            foreach ($lines as $line_num => $line) if(strpos($line,'<div id="CONT">')>0) $content=$line;

            // Correct encoding
            $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
            // Beginning useless, remove it
            $content = substr($content,strpos($content, '<div id="CONT">'));
            //One day, one row
            $jours = explode('<h2 class="color date-event">',$content);
            $mo = array("","janvier","f&eacute;vrier","mars","avril","mai","juin","juillet","ao&ucirc;t","septembre","octobre","novembre","d&eacute;cembre");
            array_shift($jours);
            foreach ($jours as $content) {
                $datetext = trim(substr($content,0,strpos($content,'</h2>')));
                $datecomplete=explode(' ',$datetext);
                $matchs=explode('<div class="ligne bb-color', $content);
                array_shift($matchs);
                foreach ($matchs as $match) {
                    preg_match('# ([0-9]+-[0-9]+)</a>#', $match,$score);

                    preg_match('#<div class="equipeDom"><img src="([a-zA-Z0-9-\./\?:]+)" alt="([\p{L&}0-9 \'-]+)">#', 
                            html_entity_decode($match, ENT_COMPAT, 'ISO-8859-1'), $domicile);
                    preg_match('#<div class="equipeExt"><img src="([a-zA-Z0-9-\./\?:]+)" alt="([\p{L&}0-9 \'-]+)">#', 
                            html_entity_decode($match, ENT_COMPAT, 'ISO-8859-1'), $exterieur);
                    preg_match('#idClub1="([0-9]+)" idClub2="([0-9]+)" idmatch="([0-9]+)"#', 
                            html_entity_decode($match, ENT_COMPAT, 'ISO-8859-1'), $ids);
                    
                    preg_match('#<a class="disabled">([0-9]+)h([0-9]+)</a>#', $match, $heure);
                    
                    if(empty($heure))
                        preg_match('#<div class="heure ">([0-9]+)h([0-9]+)[ ]*<br /></div>#', $match, $heure);

                    if(empty($heure))
                        $heure = array(1=>'20','00');
                    $sqltime = $datecomplete[3].'-'.sprintf('%02d',array_search($datecomplete[2], $mo)).'-'.sprintf('%02d',$datecomplete[1]).' '.$heure[1].':'.$heure[2].':00';

                    $team1 = $ids[1];
                    $team2 = $ids[2];

                    if(!array_key_exists($team1, $this->teams))
                    {
                        $t1 = new Team($team1, utf8_encode($domicile[2]), $domicile[1]);
                        $this->teams[$team1] = $t1;
                    }
                    else
                    {
                        $t1 = $this->teams[$team1];
                    }

                    if(!array_key_exists($team2, $this->teams))
                    {
                        $t2 = new Team($team2, utf8_encode($exterieur[2]), $exterieur[1]);
                        $this->teams[$team2] = $t2;
                    }
                    else
                    {
                        $t2 = $this->teams[$team2];
                    }
                    
                    $s = null;
                    if(!empty($score))
                    {
                        $s = new Score(
                                explode('-', $score[1])[0], 
                                explode('-', $score[1])[1], 
                                $this->getGameStateFromScore(
                                        explode('-', $score[1])[0], 
                                        explode('-', $score[1])[1]));
                    }
                    
                    $g = new Game($ids[3], $t1, $t2, $sqltime, $s);
                    $this->games[$ids[3]] = $g;
                }
            }
        }
    }

    function getGames()
    {
        return $this->games;
    }

    function getScore($gameId)
    {
        if(!empty($this->games[$gameId]))
        {
            return $this->games[$gameId]->Score;
        }

        return null;
    }

    function getGameTime($idExtGame)
    {
        if(!empty($this->games[$idExtGame]))
        {
            return $this->games[$idExtGame]->Date;
        }

        return null;
    }

    function getTeams()
    {
        return $this->teams;
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

    public function getType()
    {
        return \App\Models\Admin\iTournamentType::CHAMPIONSHIP;
    }
}