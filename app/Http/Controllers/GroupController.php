<?php namespace App\Http\Controllers;

use App\Services\GroupService;
use App\Services\GameService;
use App\Services\Contracts\ICurrentUser;
use \Redirect;
use \View;
use \Auth;
use \Validator;
use \Input;

class GroupController extends Controller 
{
    private $_groupService;
    private $_gameService;
    private $_currentUser;
    
    public function __construct(GroupService $groupService, 
            GameService $gameService,
            ICurrentUser $user)
    {
        $this->_groupService = $groupService;
        $this->_gameService = $gameService;
        $this->_currentUser = $user;
    }

    public function getIndex()
    {
        return View::make('group/index');
    }

    public function getCreate()
    {        
        return View::make('group/create');
    }

    public function postCreate()
    {
        $validator = Validator::make(
            Input::all(),
            array(
                'name' => array('required', 'min:3', 'unique:groups'),
                'description' => array('required', 'min:3'),
            )
        );
        if($validator->passes())
        {
            if(!$this->_groupService->GroupExits(Input::get('name')))
            {
                $id = $this->_groupService->CreateGroup(
                        Input::get('name'), 
                        Input::get('description'), 
                        Auth::user()->id,
                        new \DateTime(Input::get('start')),
                        new \DateTime(Input::get('end'))
                        );
                return Redirect::to('group/view/'.$id)->with(
                    'success',
                    trans('alert.succreate_group')
                );
            }
            
            return Redirect::to('group/create')->with(
                'error',
                trans('alert.nameexists')
            )->withInput();
        }

        return Redirect::to('group/create')->with(
                'error',
                trans('alert.correcterrors')
            )->withErrors($validator)->withInput();
    }

    public function getHistory($id)
    {
        $group = $this->_groupService->Get($id);
        $notifications = $this->_groupService->GetNotifications($id);
        $notifWithMessage = array();
        foreach($notifications as $n)
        {
            $notifWithMessage[] = self::formatNotificationMessage($n->type, $n->pseudo, $n->date);
        }

        return View::make('group/history')
            ->with(array('group' => $group,
                'notifications' => $notifWithMessage,
                'isMember' => $this->_groupService->IsInGroup(Auth::User()->id, $id)));
    }

    public function getInvite($id)
    {
        $group = $this->_groupService->Get($id);
        if($this->_groupService->IsInGroup(Auth::User()->id, $id))
        {
            return View::make('group/invite')
                ->with(array('group' => $group));
        }

        return Redirect::to('/group/view/' . $id)->with(
            'error',
            trans('alert.notexisting_group')
        );
    }

    public function getRequest($id)
    {
        $group = $this->_groupService->Get($id);
        if($this->_groupService->IsInGroup(Auth::User()->id, $id))
        {
            $applications = $this->_groupService->GetApplications($id);

            return View::make('group/request')
                ->with(array('group' => $group,
                    'applications' => $applications,
                    'isMember' => $this->_groupService->IsInGroup(Auth::User()->id, $id)));
        }

        return Redirect::to('/group/view/' . $id)->with(
            'error',
            trans('alert.notexisting_group')
        );
    }

    public function getView($id)
    {
        if($id > 0){
            $group = $this->_groupService->Get($id);
            return View::make('group/view')
                ->with(array('group' => $group,
                    'isMember' => $this->_groupService->IsInGroup(Auth::user()->id, $id)));
        }

        return Redirect::to('/')->with(
            'error',
            trans('alert.notexisting_group')
        );
    }

    private function formatNotificationMessage($type, $user, $date)
    {
        if($type > 0 && $type < 9)
        {
            $datetime = strtotime( $date );
            return date(trans('notifications.datetime'), $datetime).' - '.trans('notifications.type_' . $type, array('user' => $user));
        }

        throw new \InvalidArgumentException('type out of range');
    }

    public function postApply() {
        $validator = Validator::make(
            Input::all(),
            array(
                'id_group' => array('required', 'numeric'),
                'message' => array('required', 'min:3'),
            )
        );
        if($validator->passes())
        {
            $this->_groupService->ApplyForGroup(Input::get('id_group'), Input::get('message'));
            return Redirect::to('/group')->with(
                'success',
                trans('alert.sucapply_group')
            );
        }

        return Redirect::to('/')->with(
            'error',
            trans('alert.errors_application')
        );

    }

    public function postRecommand() {
        $validator = Validator::make(
            Input::all(),
            array(
                'id_group' => array('required', 'numeric'),
                'ids' => array('required'),
                'message' => array('required', 'min:3'),
            )
        );
        $ids = json_decode(Input::get('ids'));
        if($validator->passes())
        {
            if($this->_groupService->IsInGroup(Auth::User()->id, Input::get('id_group')))
            {
                foreach($ids as $id)
                {
                    $this->_groupService->RecommandForGroup($id->id, Input::get('id_group'), Input::get('message'));
                }

                return Redirect::to('/group')->with(
                    'success',
                    trans('alert.sucapply_group')
                );
            }

            return Redirect::to('group/view' . Input::get('id_group'))
                ->with(
                    'error',
                    trans('alert.notingroup')
                );
        }

        return Redirect::to('/')->with(
            'error',
            trans('alert.errors_application')
        );
    }
    
    public function getGames($id)
    {
        $games = $this->_groupService->GetGroupGames($id, 7);

        return view('group.games', array('games' => $games));
    }
    
    public function getGame($id, $action, $param)
    {
        $group = $this->_groupService->Get($id);
    
        switch($action)
        {
            case "view":
                $game = $this->_gameService->Get($param);
                $bets = $this->_groupService->GetBetsForGroupAndGame($id, $param);
                return view('group.game.view', 
                    array('group' => $group, 
                    'game' => $game, 
                    'bets' => $bets));
                
            default:
                $games = $this->_groupService->GetGroupGames($id, 7);
                return view('group.games', array('group' => $group,
                    'games' => $games));
        } 
    }
}
