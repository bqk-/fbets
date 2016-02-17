<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Services\AdminService;
use App\Services\ChampionshipService;
use App\Services\GameService;
use App\Services\BetService;
use App\Services\UserService;

class ProcessBets extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    public $MAXPOINTS = 25;
    /**
     * @var App\Services\AdminService
     */
    private $AdminService;
    
    /**
     * @var App\Services\ChampionshipService
     */
    private $ChampionshipService;
    
    /**
     * @var App\Services\GameService
     */
    private $GameService;
    
    /**
     * @var App\Services\BetService
     */
    private $BetService;
    
    /**
     * @var App\Services\UserService
     */
    private $UserService;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AdminService $adminService,
        ChampionshipService $championshipService,
        GameService $gameService,
        BetService $betService,
        UserService $userService)
    {
        $this->AdminService = $adminService;
        $this->ChampionshipService = $championshipService;
        $this->GameService = $gameService;
        $this->BetService = $betService;
        $this->UserService = $userService;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($gameId)
    {
        $game = $this->GameService->Get($gameId);
        if($game == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot find gane: ' . $gameId);
        }
        
        $championship = $this->ChampionshipService->Get($game->id_champ);
        if($championship == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot find championship: ' . $game->id_champ);
        }
        
        $score = $this->GameService->GetScore($gameId);
        if($score == null)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot find score associated to game: ' . $gameId);
        }
        
        $rates = $this->BetService->GetRates($gameId);
        
        $bets = $this->BetService->GetBetsToProcessOnGame($gameId);
        
        if($bets->count() == 0)
        {
            return;
        }

        $this->Process($championship, $bets, $score, $rates);
    }
    
    private function Process($championship, $bets, $score, $rates)
    {
        $workingClass = $this->AdminService->GetWorkingClassForChampionship($championship->id);
        $state = $workingClass->getGameStateFromScore($score->team1, $score->team2);
        $ratesArray = array();
        $ratesArray[\App\Models\Types\GameStates::HOME] = $rates->HomeRate;
        $ratesArray[\App\Models\Types\GameStates::VISITOR] = $rates->VisitRate;
        $ratesArray[\App\Models\Types\GameStates::DRAW] = $rates->DrawRate;
        
        foreach ($bets as $bet)
        {
            $stateBet = $bet->bet;
            
            if ($stateBet == $state)
            {
                $this->UserService->AddPoints($bet->id_user, ceil($this->MAXPOINTS * $ratesArray[$stateBet]));
                $this->BetService->MarkAsDone($bet->id, \App\Models\Types\BetStates::WIN);
            }
            else
            {
                $this->UserService->RemovePoints($bet->id_user, ceil($this->MAXPOINTS * $ratesArray[$stateBet]));
                $this->BetService->MarkAsDone($bet->id, \App\Models\Types\BetStates::LOOSE);
            }    
        }
    }
}
