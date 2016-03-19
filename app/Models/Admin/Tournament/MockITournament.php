<?php namespace App\Models\Admin\Tournament;

/* 
 * dumb static tournament
 * to use to test stuff other than an actual tournament
 */

class MockITournament implements ITournament
{
    private $games = array();
    private $scores = array();

    public function __construct()
    {
        $expiredForGroup = new \DateTime();
        $expiredForGroup->add(\DateInterval::createFromDateString('2 days'));
        
        $okForGroup = new \DateTime();
        $okForGroup->add(\DateInterval::createFromDateString('2 months'));
        
        $g1 = new \App\Models\Admin\TournamentClasses\Game(1, 
                new \App\Models\Admin\TournamentClasses\Team(1, 'Home1', null),
                new \App\Models\Admin\TournamentClasses\Team(2, 'Visit1', null),
                $okForGroup);
        
        $g2 = new \App\Models\Admin\TournamentClasses\Game(2, 
                new \App\Models\Admin\TournamentClasses\Team(2, 'Visit2', null),
                new \App\Models\Admin\TournamentClasses\Team(1, 'Home2', null),
                $expiredForGroup);
        
        $g3 = new \App\Models\Admin\TournamentClasses\Game(3, 
                new \App\Models\Admin\TournamentClasses\Team(2, 'Visit3', null),
                new \App\Models\Admin\TournamentClasses\Team(1, 'Home3', null),
                $okForGroup);
        
        $s1 = new \App\Models\Admin\TournamentClasses\Score(1, 0, $this->getGameStateFromScore(1, 0));
        $s2 = new \App\Models\Admin\TournamentClasses\Score(1, 0, $this->getGameStateFromScore(1, 0));
        
        $this->games = [1 => $g1, 2 => $g2, 3 => $g3];
        $this->scores = [1 => $s1, 2 => $s2];
    }
    public function getGameStateFromScore($scoreH, $scoreV)
    {
        
    }

    public function getGameTime($extIdGame)
    {
        
    }

    public function getGames()
    {
        return $this->games;
    }

    public function getScore($extIdGame)
    {
        if(key_exists($extIdGame, $this->scores))
        {
            return $this->scores[$extIdGame];
        }
        
        return null;
    }

    public function getTeams()
    {
        return array(
            1 => new \App\Models\Admin\TournamentClasses\Team(1, 'Home', null),
            2 => new \App\Models\Admin\TournamentClasses\Team(2, 'Visit', null));
    }

    public function getType()
    {
        
    }
 
}
