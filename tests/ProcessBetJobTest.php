<?php

class ProcessBetJobTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testProcessBetJob()
	{
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
        $betsService = $this->app->make('App\Services\BetService');
        $championshipService = $this->app->make('App\Services\ChampionshipService');
        $gameService = $this->app->make('App\Services\GameService');
        $userService = $this->app->make('App\Services\UserService');
        $adminService = $this->app->make('App\Services\AdminService');
        
        $cs = $championshipService->Create('Test', 'Lequipe_Football', 1);
        $adminService->UpdateChampionshipParams($cs, 
                array(
                    'http://www.lequipe.fr/Football/FootballResultat52205.html', 
                    'http://www.lequipe.fr/Football/FootballResultat52242.html'));
        
        $game = $gameService->Create(new 
                \App\Models\Admin\TournamentClasses\Game(123, 
                new \App\Models\Admin\TournamentClasses\Team(1, 'blah', 'url'),
                new \App\Models\Admin\TournamentClasses\Team(2, 'bloh', 'url'), 
                '2016-02-14', 
                new App\Models\Admin\TournamentClasses\Score(5, 5, App\Models\Types\GameStates::DRAW)),
                $cs,
                array(1 => 1, 2 => 2));
        
        $user->LogUser(2);
        $bet2 = $betsService->Create($game, App\Models\Types\GameStates::DRAW);
        
        $user->LogUser(3);
        $bet3 = $betsService->Create($game, App\Models\Types\GameStates::VISITOR);
        
        $user->LogUser(4);
        $bet4 = $betsService->Create($game, App\Models\Types\GameStates::HOME);
        
		$svc = new \App\Jobs\ProcessBets(
                $adminService, 
                 $championshipService, $gameService, $betsService, 
                $userService);
        
        $svc->handle($game);
        
        $this->assertEquals(0, $betsService->GetBetsToProcessOnGame($game)->count());
        
        $this->assertEquals(App\Models\Types\BetStates::WIN, $betsService->Get($bet2)->state);
        $this->assertEquals(App\Models\Types\BetStates::LOOSE, $betsService->Get($bet3)->state);
        $this->assertEquals(App\Models\Types\BetStates::LOOSE, $betsService->Get($bet4)->state);
        
        $this->assertEquals($userService->GetUserById(2)->points, 1000);
        $this->assertEquals($userService->GetUserById(3)->points, 1000);
        $this->assertEquals($userService->GetUserById(4)->points, 1000);
	}
}
