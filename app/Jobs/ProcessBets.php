<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Services\AdminService;
use App\Services\ChampionshipService;
use App\Services\GameService;

class ProcessBets extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AdminService $adminService,
        ChampionshipService $championshipService,
        GameService $gameService)
    {
        $this->AdminService = $adminService;
        $this->ChampionshipService = $championshipService;
        $this->GameService = $gameService;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($gameId)
    {
        $winnerScore = $this->GameService->GetScore($id);

        $bets = $game->bets();
        $nb = 0;
        foreach ($bets as $bet)
        {
            $points = 0;
            if ($bet->score1 == $bet->score2)
            {
                $winnerBet = 0;
            }
            elseif ($bet->score1 > $bet->score2)
            {
                $winnerBet = 1;
            }
            else
            {
                $winnerBet = 2;
            }

            if ($winnerBet == $winnerScore)
            {
                $points += POINTS_OUTCOME;
                $param['outcome'] = 1;
                if ($bet->score1 == $score->team1 && $bet->score2 == $score->team2)
                {
                    $points += POINTS_BONUS;
                }
            }
            else
            {
                $points -= POINTS_OUTCOME;
            }

            $bet->update($param);

            $bet->user()->increment('points', $points);
            $bet->save();
            $nb++;
        }

        return $nb;
    }
}
