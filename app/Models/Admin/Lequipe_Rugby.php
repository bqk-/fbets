<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 28/07/15
 * Time: 21:51
 */

namespace App\Models\Admin;

use App\Models\Data\Game;

class Lequipe_Rugby implements iTournament {

    private $scores = array();
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

                    if(empty($score))
                        $score[1]='-';

                    preg_match('#<div class="equipeDom"><a class="link" href="[a-zA-Z0-9-\./\?:]+"><img src="([a-zA-Z0-9-\./\?:]+)"></a><a href="[a-zA-Z0-9-\./\?:]+" class="[a-z]*">([\p{L&}0-9 \'-]+)</a>#', html_entity_decode($match, ENT_COMPAT, 'ISO-8859-1'),$domicile);
                    preg_match('#<div class="equipeExt"><a class="link" href="[a-zA-Z0-9-\./\?:]+"><img src="([a-zA-Z0-9-\./\?:]+)"></a><a href="[a-zA-Z0-9-\./\?:]+" class="[a-z]*">([\p{L&}0-9 \'-]+)</a>#', html_entity_decode($match, ENT_COMPAT, 'ISO-8859-1'),$exterieur);
                    preg_match('#<a class="disabled"> ([0-9]+)h([0-9]+)</a>#', $match,$heure);
                    if(empty($heure))
                        preg_match('#<div class="heure"><strong>([0-9]+)h([0-9]+)</strong>#', $match, $heure);

                    if(empty($heure))
                        $heure = array(1=>'20','00');
                    $sqltime = $datecomplete[3].'-'.sprintf('%02d',array_search($datecomplete[2], $mo)).'-'.sprintf('%02d',$datecomplete[1]).' '.$heure[1].':'.$heure[2].':00';

                    $team1 = crc32(utf8_encode($domicile[2]));
                    $team2 = crc32(utf8_encode($exterieur[2]));

                    $g = new Game;
                    $g->team1 = $team1;
                    $g->team2 = $team2;
                    $g->logo1 = $domicile[1];
                    $g->logo2 = $exterieur[1];
                    $g->score = $score[1];
                    $g->date = $sqltime;

                    if(!array_key_exists($team1, $this->teams))
                    {
                        $t = new Team;
                        $t->name = utf8_encode($domicile[2]);
                        $t->logo = $domicile[1];
                        $t->id = $team1;
                        $this->teams[crc32(utf8_encode($domicile[2]))] = $t;
                    }

                    if(!array_key_exists($team2, $this->teams))
                    {
                        $t = new Team;
                        $t->name = utf8_encode($exterieur[2]);
                        $t->logo = $exterieur[1];
                        $t->id = $team2;
                        $this->teams[crc32(utf8_encode($exterieur[2]))] = $t;
                    }

                    $this->games[$g->team1.'-'.$g->team2] = $g;

                    $this->scores[$g->team1.'-'.$g->team2]= $score[1];
                }
            }
        }
    }

    function getGames()
    {
        return $this->games;
    }

    function getScore($team1, $team2)
    {
        if(!empty($this->scores[$team1.'-'.$team2])){
            $s = explode('-',$this->scores[$team1.'-'.$team2]);
            if($s[0] != '' && $s[1] != '')
                return $this->scores[$team1.'-'.$team2];
        }

        return null;
    }

    function getGameTime($team1, $team2)
    {
        if(!empty($this->games[$team1.'-'.$team2]))
        {
            return $this->games[$team1.'-'.$team2]->date;
        }

        return null;
    }

    function getTeams()
    {
        return $this->teams;
    }
}