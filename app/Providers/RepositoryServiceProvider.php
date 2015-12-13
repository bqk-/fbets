<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\GameRepository;
use App\Repositories\ChampionshipRepository;
use App\Repositories\BetRepository;
use App\Repositories\GroupRepository;
use App\Repositories\PollRepository;
use App\Repositories\ScoreRepository;
use App\Repositories\SportRepository;
use App\Repositories\TeamRepository;

class RepositoryServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{   
             
	}

	/**
	 * Register the application repositories.
	 *
	 * @return void
	 */
	public function register()
	{
            $this->app->bind(
                'App\Repositories\Contracts\IChampionshipRepository',
                'App\Repositories\ChampionshipRepository'
            );
            $this->app->bind(
                'App\Repositories\Contracts\IBetRepository',
                'App\Repositories\BetRepository'
            );
            $this->app->bind(
                'App\Repositories\Contracts\IGameRepository',
                'App\Repositories\GameRepository'
            );
            $this->app->bind(
                'App\Repositories\Contracts\IGroupRepository',
                'App\Repositories\GroupRepository'
            );
            
            $this->app->bind(
                'App\Repositories\Contracts\IPollRepository',
                'App\Repositories\PollRepository'
            );
            $this->app->bind(
                'App\Repositories\Contracts\IScoreRepository',
                'App\Repositories\ScoreRepository'
            );
            $this->app->bind(
                'App\Repositories\Contracts\ISportRepository',
                'App\Repositories\SportRepository'
            );
            $this->app->bind(
                'App\Repositories\Contracts\ITeamRepository',
                'App\Repositories\TeamRepository'
            );
            $this->app->bind(
                'App\Repositories\Contracts\IUserRepository',
                'App\Repositories\UserRepository'
            );
	}

}
