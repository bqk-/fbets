<?php namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\Data\Game;
use App\Repositories\Contracts\IGameRepository;
use App\Models\Data\Suggestion;
use App\Models\Data\GameRelation;
use \DB;

class GameRepository implements IGameRepository
{

    public function GetGamesWithNoScore($championship = null)
    {
        $baseQ = $g = Game::select('games.*','championships.name',DB::raw('COUNT(bets.id) as bets'))
            ->where('date','<=',DB::raw('NOW() - INTERVAL 3 HOUR'))
            ->leftJoin('championships','championships.id','=','games.id_championship')
            ->leftJoin('results','results.id_game','=','games.id')
            ->leftJoin('bets','bets.id_game','=','games.id')
            ->whereNull('results.id')
            ->groupBy('games.id')
            ->orderBy('id_championship','desc');

        if($championship != null)
        {
            $baseQ = $baseQ->where('id_championship', '=', $championship);
        }

        return $baseQ->get();
    }

    public function UpdateGameTime($idGame, $gameTime)
    {
        $game = $this->Get($idGame);

        if($gameTime == '')
        {
            throw new \InvalidArgumentException('Feed me with a time or fail');
        }

        $game = $this->Get($idGame);
        $game->date = $gameTime;
        $game->save();
    }

    public function Get($id)
    {
        $g = Game::find($id);

        if($g == null)
        {
            throw new NotFoundException('Game',  'id', $id);
        }

        return $g;
    }

    public function GetNext7DaysGameAllSport() 
    {
        return Game::with('team1')->with('team2')->with('championship.sport')->with('bets')
            ->where('date', '>', DB::raw('CURDATE()'))
            ->where('date', '<', DB::raw('CURDATE() + INTERVAL 7 DAY'))
            ->orderBy('date','asc')
            ->get();
    }

    public function DropGamesForChampionship($id) 
    {
        return Game::where('id_championship', '=', $id)->delete();
    }

    public function GetUserSuggestions($id) 
    {
        return Suggestion::where('id_user', '=', $id)->get();
    }

    public function Suggest($sport, $team1, $team2, $event, $date, $userId) 
    {
        $s = new Suggestion;
        $s->id_sport = $sport;
        $s->team1 = $team1;
        $s->team2 = $team2;
        $s->championship = $event;
        $s->id_user = $userId;
        $s->date = $date;
        $s->state = 0;
        $s->save();
    }

    public function Create($teamh, $teamv, $champId, $date)
    {
        $g = new Game;
        $g->team1 = $teamh;
        $g->team2 = $teamv;
        $g->id_championship = $champId;
        $g->date = $date;
        $g->save();
        
        return $g->id;
    }

    public function CreateRelation($outId, $localId)
    {
        $r = new GameRelation;
        $r->local_id = $localId;
        $r->out_id = $outId;
        $r->save();
        
        return $r->id;
    }

    public function GetAllGames($idChamp)
    {
        return Game::where('id_championship', '=', $idChamp)->get();
    }

    public function SaveRates($idGame, $rHome, $rDraw, $rVisit)
    {
        $g = $this->Get($idGame);
        $g->rate_home = $rHome;
        $g->rate_draw = $rDraw;
        $g->rate_visit = $rVisit;
        $g->save();
    }

}