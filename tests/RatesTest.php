<?php

class RatesTest extends TestCase
{
    /**
     * Testing the rates
     *
     * @return void
     */
    public function testRates1Bet()
    {
        $svcGame = $this->app->make('App\Services\GameService');
        $id = $svcGame->Create(new App\Models\Admin\TournamentClasses\Game(1,
                            new App\Models\Admin\TournamentClasses\Team(1,
                                                                        'Test',
                                                                        ''),
                            new App\Models\Admin\TournamentClasses\Team(2,
                                                                        'Test2',
                                                                        ''),
                            '2100-10-10'), 1, array(1 => 1, 2 => 2));
        $svc = $this->app->make('App\Services\BetService');
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        
        $percent = $svc->GetPercent($id);
        $rates = $svc->GetRates($id);

        $sumP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        $rateP = round($rates->HomeRate + $rates->VisitRate + $rates->DrawRate, 2);

        $this->assertEquals(1, $sumP);
        $this->assertEquals(1, $rateP);
        $this->assertEquals(0.1, $rates->DrawRate);
        $this->assertEquals(0.45, $rates->HomeRate);
        $this->assertEquals(0.45, $rates->VisitRate);
       
        $this->assertEquals(1, $percent->DrawRate);
        $this->assertEquals(0, $percent->HomeRate);
        $this->assertEquals(0, $percent->VisitRate);
    }
    
    public function testRates2Bets()
    {
        $svcGame = $this->app->make('App\Services\GameService');
        $id = $svcGame->Create(new App\Models\Admin\TournamentClasses\Game(1,
                            new App\Models\Admin\TournamentClasses\Team(1,
                                                                        'Test',
                                                                        ''),
                            new App\Models\Admin\TournamentClasses\Team(2,
                                                                        'Test2',
                                                                        ''),
                            '2100-10-10'), 1, array(1 => 1, 2 => 2));
        $svc = $this->app->make('App\Services\BetService');
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        
        $percent = $svc->GetPercent($id);
        $rates = $svc->GetRates($id);
        
        $sumP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        $rateP = round($rates->HomeRate + $rates->VisitRate + $rates->DrawRate, 2);
        
        $this->assertEquals(1, $sumP);
        $this->assertEquals(1, $rateP);
        $this->assertEquals(0.1, $rates->DrawRate);
        $this->assertEquals(0.45, $rates->HomeRate);
        $this->assertEquals(0.45, $rates->VisitRate);
       
        $this->assertEquals(1, $percent->DrawRate);
        $this->assertEquals(0, $percent->HomeRate);
        $this->assertEquals(0, $percent->VisitRate);
    }
    
    
    public function testRates9Bets()
    {
        $svcGame = $this->app->make('App\Services\GameService');
        $id = $svcGame->Create(new App\Models\Admin\TournamentClasses\Game(1,
                            new App\Models\Admin\TournamentClasses\Team(1,
                                                                        'Test',
                                                                        ''),
                            new App\Models\Admin\TournamentClasses\Team(2,
                                                                        'Test2',
                                                                        ''),
                            '2100-10-10'), 1, array(1 => 1, 2 => 2));
        $svc = $this->app->make('App\Services\BetService');
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::HOME);
        $svc->Create($id, App\Models\Types\GameStates::HOME);
        $svc->Create($id, App\Models\Types\GameStates::HOME);
        $svc->Create($id, App\Models\Types\GameStates::VISITOR);
        $svc->Create($id, App\Models\Types\GameStates::VISITOR);
        $svc->Create($id, App\Models\Types\GameStates::VISITOR);
        
        $percent = $svc->GetPercent($id);
        $rates = $svc->GetRates($id);
        
        $sumP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        $rateP = round($rates->HomeRate + $rates->VisitRate + $rates->DrawRate, 2);
        
        $this->assertEquals(1, $sumP);
        $this->assertEquals(1, $rateP);
        $this->assertEquals(0.333, $rates->DrawRate);
        $this->assertEquals(0.333, $rates->HomeRate);
        $this->assertEquals(0.333, $rates->VisitRate);
       
        $this->assertGreaterThan(0.32, $percent->DrawRate);
        $this->assertGreaterThan(0.32, $percent->HomeRate);
        $this->assertGreaterThan(0.32, $percent->VisitRate);
    }
    
    
    public function testRates10Bets()
    {
        $svcGame = $this->app->make('App\Services\GameService');
        $id = $svcGame->Create(new App\Models\Admin\TournamentClasses\Game(1,
                            new App\Models\Admin\TournamentClasses\Team(1,
                                                                        'Test',
                                                                        ''),
                            new App\Models\Admin\TournamentClasses\Team(2,
                                                                        'Test2',
                                                                        ''),
                            '2100-10-10'), 1, array(1 => 1, 2 => 2));
        $svc = $this->app->make('App\Services\BetService');
        $svc->Create($id, App\Models\Types\GameStates::HOME);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        $svc->Create($id, App\Models\Types\GameStates::DRAW);
        
        $percent = $svc->GetPercent($id);
        $rates = $svc->GetRates($id);
        
        $sumP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        $rateP = round($rates->HomeRate + $rates->VisitRate + $rates->DrawRate, 2);
        
        $this->assertEquals(1, $sumP);
        $this->assertEquals(1, $rateP);
        $this->assertEquals(0.1, $rates->DrawRate);
        $this->assertEquals(0.426, $rates->HomeRate);
        $this->assertEquals(0.474, $rates->VisitRate);
       
        $this->assertEquals(0.9, $percent->DrawRate);
        $this->assertEquals(0.1, $percent->HomeRate);
        $this->assertEquals(0, $percent->VisitRate);
    }
}