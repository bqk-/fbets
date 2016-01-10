<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Models\Data\User;
use \Auth;
use \View;
use \DB;
use App\Services\GroupService;
use App\Models\Data\Suggestion;
use App\Models\Data\Game;

class AjaxController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Ajax Controller
    |--------------------------------------------------------------------------
    |
    | Manage everything ajax related
    |
    |    Route::controller('ajax', 'AjaxController');
    |
    */
    private $_groupService;

    public function __construct(GroupService $groupService)
    {
        $this->_groupService = $groupService;
    }

    //Not used anymore, keep it in case
    /*
    public function getWeekgames()
    {
        $max = DB::table('games')->where('id_championship','=',Input::get('champ'))->max('week');
        $week = min($max, Input::get('week'));
        if($week>0)
            return View::make('ajax/weekgames')->with('week',$week)->with('champ',Input::get('champ'));
        elseif(!empty(Input::get('champ')))
            return View::make('ajax/weekgames')->with('week',$max)->with('champ',Input::get('champ'));
    }
    */

    public function getProgress(){
        return View::make('ajax/progress');
    }

    public function getAutocomplete($type = null, $param = null) {
        if(!AdminController::isAdmin()){
            return null;
        }
        return View::make('ajax/autocomplete')->with(array('type'=>$type, 'param'=>$param));
    }

    //TODO: avoid dumb returns
    public function getSuggestions($status = null, $id = null, $c = null)
    {
        if(!AdminController::isAdmin())
        {
            return null;
        }

        switch ($status)
        {
            case 'ok':
                if($id != null && $id > 0 && $c != null && $c > 0){
                    $s = Suggestion::find($id);
                    $s->state = 1;
                    $g = new Game;
                    $g->team1 = $s->team1;
                    $g->team2 = $s->team2;
                    $g->date = $s->date;
                    $g->logo1 = intval(DB::table('games')->where('team1','=',$g->team1)->max('logo1'));
                    $g->logo2 = intval(DB::table('games')->where('team2','=',$g->team2)->max('logo2'));
                    $g->week = 0;
                    $g->id_championship = $c;
                    //Save everything
                    $s->save();
                    $g->save();
                    return 'something'; //else error
                }
                break;

            case 'no':
                if($id != null && $id > 0) {
                    $s = Suggestion::find($id);
                    $s->state = 2;
                    $s->save();
                    return 'something'; //else error
                }
                break;
            
            default:
                break;
        }

        return null;
    }

    public function getUsers($search = '')
    {
        if (strlen($search) > 1)
        {
            $users = User::where('pseudo', 'LIKE', '%' . $search . '%')->orWhere('display', 'LIKE', '%' . $search . '%')->select('id', 'pseudo', 'display')->get();
            $ret = array();
            foreach ($users as $u) if (!Auth::check() || $u->id != Auth::User()->id)
            {
                $ret[$u->id] = $u->display . '(' . $u->pseudo . ')';
            }

            return json_encode($ret);
        }
        else
        {
            return json_encode(array());
        }
    }
}
