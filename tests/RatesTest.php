<?php

class RatesTest extends TestCase
{
    /** TODO: betservice - limit 1 bet/user
     * Register a test user
     *
     * @return void
     */
    public function testRates1Bet()
    {
        $svc = $this->app->make('App\Services\BetService');
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        
        $percent = $svc->GetPercent(1);
        $rates = $svc->GetRates(1);
        
        $sumP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        $rateP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;

        $this->assertEquals(1, $sumP);
        $this->assertEquals(1, $rateP);
        $this->assertEquals(0.8, $rates->DrawRate);
        $this->assertEquals(0.1, $rates->HomeRate);
        $this->assertEquals(0.1, $rates->VisitRate);
       
        $this->assertEquals(1, $percent->DrawRate);
        $this->assertEquals(0, $percent->HomeRate);
        $this->assertEquals(0, $percent->VisitRate);
    }
    
    public function testRates2Bets()
    {
        $svc = $this->app->make('App\Services\BetService');
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        
        $percent = $svc->GetPercent(1);
        $rates = $svc->GetRates(1);
        
        $sumP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        $rateP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        
        $this->assertEquals(1, $sumP);
        $this->assertEquals(1, $rateP);
        $this->assertEquals(0.8, $rates->DrawRate);
        $this->assertEquals(0.1, $rates->HomeRate);
        $this->assertEquals(0.1, $rates->VisitRate);
       
        $this->assertEquals(1, $percent->DrawRate);
        $this->assertEquals(0, $percent->HomeRate);
        $this->assertEquals(0, $percent->VisitRate);
    }
    
    
    public function testRates9Bets()
    {
        $svc = $this->app->make('App\Services\BetService');
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::HOME);
        $svc->Create(1, App\Models\Types\GameStates::HOME);
        $svc->Create(1, App\Models\Types\GameStates::HOME);
        $svc->Create(1, App\Models\Types\GameStates::VISITOR);
        $svc->Create(1, App\Models\Types\GameStates::VISITOR);
        $svc->Create(1, App\Models\Types\GameStates::VISITOR);
        
        $percent = $svc->GetPercent(1);
        $rates = $svc->GetRates(1);
        
        $sumP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        $rateP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        
        $this->assertEquals(1, $sumP);
        $this->assertEquals(1, $rateP);
        $this->assertGreaterThan(0.32, $rates->DrawRate);
        $this->assertGreaterThan(0.32, $rates->HomeRate);
        $this->assertGreaterThan(0.32, $rates->VisitRate);
       
        $this->assertGreaterThan(0.32, $percent->DrawRate);
        $this->assertGreaterThan(0.32, $percent->HomeRate);
        $this->assertGreaterThan(0.32, $percent->VisitRate);
    }
    
    
    public function testRates10Bets()
    {
        $svc = $this->app->make('App\Services\BetService');
        $svc->Create(1, App\Models\Types\GameStates::HOME);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        $svc->Create(1, App\Models\Types\GameStates::DRAW);
        
        $percent = $svc->GetPercent(1);
        $rates = $svc->GetRates(1);
        
        $sumP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        $rateP = $percent->HomeRate + $percent->VisitRate + $percent->DrawRate;
        
        $this->assertEquals(1, $sumP);
        $this->assertEquals(1, $rateP);
        $this->assertEquals(0.775, $rates->DrawRate);
        $this->assertEquals(0.125, $rates->HomeRate);
        $this->assertEquals(0.1, $rates->VisitRate);
       
        $this->assertEquals(0.9, $percent->DrawRate);
        $this->assertEquals(0.1, $percent->HomeRate);
        $this->assertEquals(0, $percent->VisitRate);
    }
}