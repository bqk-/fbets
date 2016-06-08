<?php

class GroupGameTest extends TestCase 
{
    public function testAddGameToGroup()
	{   
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$srv = $this->app->make('App\Services\GroupService');
        $pollSrv = $this->app->make('App\Services\PollService');
       
        $championshipService = $this->app->make('App\Services\ChampionshipService');
        $gameService = $this->app->make('App\Services\GameService');
        $adminService = $this->app->make('App\Services\AdminService');
        $teamService = $this->app->make('App\Services\TeamService');
        
        $cs = $championshipService->Create('Test', 'MockITournament', 1);
        $svc = new \App\Jobs\UpdateChampionship($cs->id);
        
        $svc->handle($adminService, 
                $championshipService, 
                $gameService, 
                $teamService);
        
        $this->assertEquals($gameService->GetAllGames($cs->id)->count(), 3);
        $start = new DateTime();
        $end = new DateTime();
        $start->add(DateInterval::createFromDateString('1 month'));
        $end->add(DateInterval::createFromDateString('6 months'));
        
        $grp = $srv->CreateGroup('group test', 'plop plop plop',
                 $start, $end);
        $poll = $srv->SuggestGameForGroup($grp, 2);
        
        $pollSrv->AddVote($poll, App\Models\Types\VoteTypes::YES);
        
        $job = new \App\Jobs\ClosePoll($poll);
        $job->handle($pollSrv, $srv);
	}
    
    /**
    * @expectedException \App\Exceptions\InvalidOperationException
    * @expectedExceptionMessage Invalid operation: Game is not in the interval.
    */
    
    /* Removed, not applicable yet
    public function testAddGamesToGroupAndGameIsNotInTheInterval()
	{   
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$srv = $this->app->make('App\Services\GroupService');
        $pollSrv = $this->app->make('App\Services\PollService');
       
        $championshipService = $this->app->make('App\Services\ChampionshipService');
        $gameService = $this->app->make('App\Services\GameService');
        $adminService = $this->app->make('App\Services\AdminService');
        $teamService = $this->app->make('App\Services\TeamService');
        
        $cs = $championshipService->Create('Test', 'MockITournament', 1);
        $svc = new \App\Jobs\UpdateChampionship($cs->id);
        
        $svc->handle($adminService, 
                $championshipService, 
                $gameService, 
                $teamService);
        
        $this->assertEquals($gameService->GetAllGames($cs->id)->count(), 3);
        $start = new DateTime();
        $start->add(DateInterval::createFromDateString('1 week'));
        $end = new DateTime();
        $end->add(DateInterval::createFromDateString('6 months'));
        
        $grp = $srv->CreateGroup('group test', 'plop plop plop',
                 $start, $end);
        $poll = $srv->SuggestGameForGroup($grp, 3);
	}
     * 
     */
}
