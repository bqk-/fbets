<?php

class UpdateChampionshipJobTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testUpdateChampionshipJob()
	{        
        $championshipService = $this->app->make('App\Services\ChampionshipService');
        $gameService = $this->app->make('App\Services\GameService');
        $adminService = $this->app->make('App\Services\AdminService');
        $teamService = $this->app->make('App\Services\TeamService');
        
        $cs = $championshipService->Create('Test', 'Lequipe_Football', 1);
        $adminService->UpdateChampionshipParams($cs->id, 
                array(
                    'http://www.lequipe.fr/Football/FootballResultat52205.html', 
                    'http://www.lequipe.fr/Football/FootballResultat52242.html'));

		$svc = new \App\Jobs\UpdateChampionship($cs->id);
        
        $svc->handle($adminService, 
                $championshipService, 
                $gameService, 
                $teamService);
        
        $this->assertEquals($gameService->GetAllGames($cs->id)->count(), 380);
	}
    
    public function testEquipeRugby()
	{        
        $championshipService = $this->app->make('App\Services\ChampionshipService');
        $gameService = $this->app->make('App\Services\GameService');
        $adminService = $this->app->make('App\Services\AdminService');
        $teamService = $this->app->make('App\Services\TeamService');
        
        $cs = $championshipService->Create('Test', 'Lequipe_Rugby', 1);
        $adminService->UpdateChampionshipParams($cs->id, 
                array(
                    'http://www.lequipe.fr/Rugby/RugbyResultat7543.html', 
                    'http://www.lequipe.fr/Rugby/RugbyResultat7568.html'));

		$svc = new \App\Jobs\UpdateChampionship($cs->id);
        
        $svc->handle($adminService, 
                $championshipService, 
                $gameService, 
                $teamService);
        
        $this->assertEquals($gameService->GetAllGames($cs->id)->count(), 182);
	}
}
