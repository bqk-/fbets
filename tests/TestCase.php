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
               
//        require_once __DIR__ . '/../app/Repositories/Contracts/IChampionshipRepository.php';
//        require_once __DIR__ . '/../app/Repositories/Contracts/IBetRepository.php';
//        require_once __DIR__ . '/../app/Repositories/Contracts/IGameRepository.php';
//        require_once __DIR__ . '/../app/Repositories/Contracts/IGroupRepository.php';
//        require_once __DIR__ . '/../app/Repositories/Contracts/IPollRepository.php';
//        require_once __DIR__ . '/../app/Repositories/Contracts/IScoreRepository.php';
//        require_once __DIR__ . '/../app/Repositories/Contracts/ISportRepository.php';
//        require_once __DIR__ . '/../app/Repositories/Contracts/ITeamRepository.php';
//        require_once __DIR__ . '/../app/Repositories/Contracts/IRepository.php';
//        require_once __DIR__ . '/../app/Models/Services/Contracts/ICurrentUser.php';
        
        require_once __DIR__ . '/Mock/MockChampionshipRepository.php';
        require_once __DIR__ . '/Mock/MockBetRepository.php';
        require_once __DIR__ . '/Mock/MockGameRepository.php';
        require_once __DIR__ . '/Mock/MockGroupRepository.php';
        require_once __DIR__ . '/Mock/MockPollRepository.php';
        require_once __DIR__ . '/Mock/MockScoreRepository.php';
        require_once __DIR__ . '/Mock/MockSportRepository.php';
        require_once __DIR__ . '/Mock/MockTeamRepository.php';
        require_once __DIR__ . '/Mock/MockUserRepository.php';
        require_once __DIR__ . '/Mock/MockUser.php';
        require_once __DIR__ . '/Mock/MockImageService.php';
        
        $this->baseUrl = 'http://homestead.app';
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
