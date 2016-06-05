<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    protected $baseUrl;
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }
        
    public function setUp()
    {
        parent::setUp();

        $this->baseUrl = 'http://localhost';
        $this->app->singleton(
                'App\Repositories\Contracts\IChampionshipRepository', 
                'Mock\MockChampionshipRepository'
            );
            $this->app->singleton(
                'App\Repositories\Contracts\IBetRepository',
                'Mock\MockBetRepository'
            );
            $this->app->singleton(
                'App\Repositories\Contracts\IGameRepository',
                'Mock\MockGameRepository'
            );
            $this->app->singleton(
                'App\Repositories\Contracts\IGroupRepository',
                'Mock\MockGroupRepository'
            );
            $this->app->singleton(
                'App\Repositories\Contracts\IPollRepository',
                'Mock\MockPollRepository'
            );
            $this->app->singleton(
                'App\Repositories\Contracts\IScoreRepository',
                'Mock\MockScoreRepository'
            );
            $this->app->singleton(
                'App\Repositories\Contracts\ISportRepository',
                'Mock\MockSportRepository'
            );
            $this->app->singleton(
                'App\Repositories\Contracts\ITeamRepository',
                'Mock\MockTeamRepository'
            );
            $this->app->singleton(
                'App\Repositories\Contracts\IUserRepository',
                'Mock\MockUserRepository'
            );
            $this->app->singleton(
                'App\Services\Contracts\IImageService',
                'Mock\MockImageService'
            );
            $this->app->singleton(
                'App\Services\Contracts\ICurrentUser',
                'Mock\MockUser'
            );
            
    }
}
