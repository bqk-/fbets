<?php namespace App\Http\Controllers;

use App\Exceptions\InvalidArgumentException;
use App\Exceptions\InvalidOperationException;
use App\Exceptions\OutOfRangeException;
use App\Services\AdminService;
use App\Services\ChampionshipService;
use App\Services\GameService;
use App\Services\Contracts\IImageService;
use App\Services\SportService;
use App\Services\TeamService;
use \Auth;
use App\Models\Data\Suggestion;
use \View;
use \Redirect;
use \Input;
use \Validator;
use Illuminate\Support\Facades\Queue;

class AdminController extends Controller {
    public static $admins = array(1,2);
    /*
    |--------------------------------------------------------------------------
    | Administration Controller
    |--------------------------------------------------------------------------
    |
    | Manage everything admin related
    |
    |   Route::filter('admin', function()
    |    {
    |        if(!(Auth::check() && in_array(Auth::user()->id,AdminController::$admins)))
    |            return Redirect::to('/');
    |    });
    |
    |    Route::when('admin*', 'admin');
    |    Route::controller('admin', 'AdminController');
    |
    */

    private $_adminService;
    private $_imageService;
    private $_gameService;
    private $_championshipService;
    private $_teamService;
    private $_sportService;

    public function __construct(AdminService $adminService, IImageService $imageService, GameService $gameService,
                                    ChampionshipService $championshipService, TeamService $teamService, SportService
        $sportService)
    {
        $this->_adminService = $adminService;
        $this->_imageService = $imageService;
        $this->_gameService = $gameService;
        $this->_championshipService = $championshipService;
        $this->_teamService = $teamService;
        $this->_sportService = $sportService;
    }

    public function getIndex()
    {
        $suggestions = Suggestion::where('state','=',0)->count('id');
        return View::make('admin/home')->with(array('suggestions'=>$suggestions));
    }

    public function getController(){
        $rc = new \ReflectionClass('AdminController');
        return View::make('admin/controller')->with(array('rc'=>$rc));
    }

    public function postController(){
        $function = Input::get('method');
        $return = call_user_func_array(array($this, $function), Input::get('param'));
        return View::make('admin/controller')->with(array('return'=>$return, 'succes'=>'Methode exécutée.', 'rc'=>null));
    }

    public function postIndex() 
    {
        if(Input::has('games'))
        {
            foreach (Input::get('games') as $id => $val)
            {
                if ($val['team1'] !== '' && $val['team2'] !== '')
                {
                    $this->_gameService->AddScore($id, $val['team1'], $val['team2']);
                }
            }
        }

        return Redirect::to('admin/')->with('success', 'Scores ajoutés !');
    }

    public function getNewChamp()
    {
        $classes = $this->_adminService->GetAvailableClasses();
        $sports = $this->_sportService->GetSportsForDropdown();
        return View::make('admin/newChampionship', array('classes' => $classes, 'sports' => $sports));
    }

    public function postNewChamp()
    {
        $validator = Validator::make(
            Input::all(),
            array(
                'name' => 'Required',
                'class' => 'Required',
                'sport' => 'Required'
            )
        );
        if($validator->passes())
        {
            $championship = $this->_championshipService->Create(Input::get('name'), Input::get('class'), Input::get
            ('sport'));

            $params = $this->_adminService->GetConstructorParamsFromClassname(Input::get('class'));

            return View::make('admin/reloadGames', 
                    array('championship' => $championship, 'params' => $params, 'dbParams' => null));
        }

        return Redirect::to('admin/new-champ')->with('error','Missing fields')->withErrors
        ($validator);
    }

    public function getNewGame()
    {
        $teams = $this->_teamService->GetTeamsForDropdown();
        $championships = $this->_championshipService->GetAll()->lists('name', 'id');
        return View::make('admin/newGame', array('teams' => $teams, 'championships' => $championships));
    }

    public function postNewGame(){
        $validator = Validator::make(
            Input::all(),
            array(
                'event' => 'Required',
                'team1' => 'Required',
                'team2' => 'Required',
                'date' => 'Required',
                'time' => 'Required'
            )
        );
        if($validator->passes())
        {
            $this->_gameService->CreateFromPost(Input::get('team1'), Input::get('team2'), Input::get('event'), Input::get
            ('data'), Input::get('time'));

            return Redirect::to('admin/')->with('success','Game added !');
        }

        return Redirect::to('admin/new-game')->with('error','Missing fields.')->withErrors
        ($validator);
    }

    public function getNewSport()
    {
        return View::make('admin/newSport');
    }

    public function postNewSport()
    {
        $validator = Validator::make(
            Input::all(),
            array(
                'name' => 'Required',
                'logo' => 'Required'
            )
        );
        if ($validator->passes())
        {
            $id = $this->_imageService->UploadLogo(Input::file('logo')->getRealPath());
            $this->_sportService->Create(Input::get('name'), $id);

            return Redirect::to('admin/view/sport')->with('success', 'Sport added.');
        }

        return Redirect::to('admin/new-sport')->with('error', 'Tous les champs doivent être remplis.');
    }

    public function getViewSports()
    {
        $sports = $this->_sportService->GetAll();
        return View::make('admin/viewSports', array('sports' => $sports));
    }

    public function getViewSport($id)
    {
        $sport = $this->_sportService->GetSport($id);
        return View::make('admin/viewSport', array('sport' => $sport));
    }

    public function getViewGames($id)
    {
        $championship = $this->_championshipService->Get($id);
        $games = $championship->games;
        return View::make('admin/viewGames', array('championship' => $championship, 'games' => $games));
    }

    public function getViewChampionships()
    {
        $championships = $this->_championshipService->GetAll();
        return View::make('admin/viewChampionships', array('championships' => $championships));
    }

    public function getViewChampionship($id)
    {
        $championship = $this->_championshipService->Get($id);
        return View::make('admin/viewChampionship', array('championship' => $championship));
    }

    public function postSaveChamp()
    {
        if(Input::has('id_champ') && Input::get('id_champ') > 0)
        {
            $this->_adminService->UpdateChampionshipParams(Input::get('id_champ'), Input::get('param'));
            Queue::push(new \App\Jobs\UpdateChampionship(Input::get('id_champ')));
            
            return Redirect::to('admin/')->with('success', 'Parameters changed, '
                    . 'called worker to initialize.');
        }

        return Redirect::to('admin/')->with('error', 'No championship');
    }

    public function getEnterResults()
    {
        $games = $this->_adminService->getMissingResults();
        return View::make('admin/enterresults')->with(array('games' => $games));
    }

    public function getToggleChamp($id)
    {
        $this->_adminService->ToggleActiveChampionship($id);
        return Redirect::to('admin/view-championship/' . $id)->with('Success', 'Changed championship status.');
    }

    public static function isAdmin(){
        if(Auth::check() && in_array(Auth::user()->id, self::$admins))
            return true;
        else
            return false;
    }

    public function getImages()
    {
        $images = $this->_imageService->GetAllImages();
        $games1 = $this->_imageService->GetTeam1WithoutImage();
        $games2 = $this->_imageService->GetTeam1WithoutImage();
        return View::make('admin/images')->with(array('images' => $images, 
            'games1' => $games1, 'games2' => $games2));
    }

    public function postMailsender()
    {
        $validator = Validator::make(
        Input::all(),
        array(
            'email' => 'Email|Required',
            'subject' => 'Required',
            'message' => 'Required'
        )
        );
        if ($validator->passes())
        {
            $input = Input::all();
            Mail::send('emails/newsletter', array('msg' => $input['message']),
                       function($message) use ($input)
            {
                $message->to($input['email'])->subject($input['subject']);
            });
            return Redirect::to('admin/mailsender')->with(array('success' => 'Mail envoyé !'));
        }
        else
        {
            return Redirect::to('admin/mailsender')->with(array('error' => 'Corrige les erreurs !'))->withErrors($validator);
        }
    }

    public function getMailsender(){
        return View::make('admin/mailsender');
    }

    public function getEdit($type = null, $id = null){
        switch($type)
        {
            case 'game':
                if($id>0)
                {
                    $g = $this->_gameService->Get($id);
                    return View::make('admin/edit')->with(array('g' => $g));
                }

                return Redirect::to('admin/');
            break;

            default:
            break;
        }

        throw new OutOfRangeException('type', $type);
    }

    public function getSuggestions(){
        $s = Suggestion::all();
        return View::make('admin/suggestions')->with(array('suggestions'=>$s));
    }

    public function getCronUpdate($password)
    {
        if($password == '&*($@!Y$Hbw;][.,.d/r!$&*(243289ybb12')
        {
            $this->_adminService->UpdateAllActiveChampionships();
        }

        throw new InvalidOperationException('Incorrect Password: ' . $password);
    }

    public function getReloadGames($idChampionship)
    {
        $model = $this->_adminService->GetChampionshipConstructorParams($idChampionship);

        return View::make('admin/reloadGames', array('championship' => $model->GetChampionship(),
            'params' => $model->GetParams(),
            'dbParams' => $model->GetDbParams()));
    }

    public function postReloadGames()
    {
        if(Input::has('championship') && Input::get('championship') > 0)
        {
            $this->_adminService->UpdateChampionshipParams(Input::get('championship'), Input::get('param') == null ? array() : Input::get('param'));
            $model = $this->_adminService->GetChampionshipConstructorParams(Input::get('championship'));
            $workingClass = $this->_adminService->GetWorkingClassForChampionship(
                    Input::get('championship'),
                    Input::get('param'));

            $games = $workingClass->getGames();
            $teams = $workingClass->getTeams();
            $existingTeams = $this->_teamService->GetTeamsForDropdown($model->GetChampionship()->id_sport);
            $relations = $this->_teamService->GetRelations($model->GetChampionship()->id);

            $existingTeams->prepend('NEW', 0);
          
            return View::make('admin/confirmGames',
                array('championship' => $model->GetChampionship(),
                        'teams' => $teams,
                        'games' => $games,
                        'existingTeams' => $existingTeams,
                        'params' => $model->GetParams(),
                        'usedParams' => Input::get('param'),
                        'relations' => $relations));
        }

        return Redirect::to('admin');
    }

    public function getDropGames($idChampionship)
    {
        $championship = $this->_championshipService->Get($idChampionship);
        return View::make('admin/confirm', array('action' => 'drop-games', 'hidden' => $championship->id));
    }

    public function postDropGames()
    {
        if(Input::has('hidden') && Input::get('hidden') > 0)
        {
            $this->_adminService->DropGamesForChampionship(Input::get('hidden'));
            return Redirect::to('admin/view-championship/' . Input::get('hidden'))->with(array('success'=>'Games
            deleted'));
        }

        return Redirect::to('admin');
    }
    
    public function getRefreshGames($id)
    {
        Queue::push(new \App\Jobs\UpdateChampionship($id));
            
        return Redirect::to('admin/view-championship/' . $id)->with('success', 'Called worker to refresh.');
    }
}
