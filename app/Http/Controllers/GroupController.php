<?php namespace App\Http\Controllers;

use App\Services\GroupService;
use App\Services\GameService;
use App\Services\PollService;
use App\Services\Contracts\ICurrentUser;
use \Redirect;
use \View;
use \Validator;
use \Input;

class GroupController extends Controller 
{
    private $_groupService;
    private $_gameService;
    private $_currentUser;
    private $_pollService;
    
    public function __construct(GroupService $groupService, 
            GameService $gameService,
            PollService $pollService,
            ICurrentUser $user)
    {
        $this->_groupService = $groupService;
        $this->_gameService = $gameService;
        $this->_currentUser = $user;
        $this->_pollService = $pollService;
    }

    public function getIndex()
    {
        return View::make('group/index');
    }
    
    public function getList()
    {
        $groups = $this->_groupService->GetAll();
        foreach ($groups as $g)
        {
            $g->hasApplication = $this->_groupService->HasApplication($this->_currentUser->GetId(), $g->id);
        }
        
        return View::make('group.list', array('groups' => $groups));
    }

    public function getCreate()
    {        
        return View::make('group.create');
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
                        new \DateTime,
                        new \DateTime
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
            $notifWithMessage[] = self::formatNotificationMessage($n->type, $n->pseudo, $n->date, $id);
        }

        return View::make('group.history')
            ->with(array('group' => $group,
                'notifications' => $notifWithMessage,
                'isMember' => $this->_groupService->IsInGroup($this->_currentUser->GetId(), $id)));
    }

    public function getInvite($id)
    {
        $group = $this->_groupService->Get($id);
        if($this->_groupService->IsInGroup($this->_currentUser->GetId(), $id))
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
        if($this->_groupService->IsInGroup($this->_currentUser->GetId(), $id))
        {
            $applications = $this->_groupService->GetApplications($id);

            return View::make('group/request')
                ->with(array('group' => $group,
                    'applications' => $applications,
                    'isMember' => $this->_groupService->IsInGroup($this->_currentUser->GetId(), $id)));
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
            return View::make('group.view')
                ->with(array('group' => $group,
                    'isMember' => $this->_groupService->IsInGroup($this->_currentUser->GetId(), $id),
                    'hasApplication' => $this->_groupService->HasApplication($this->_currentUser->GetId(),
                                                                             $id)));
        }

        return Redirect::to('/')->with(
            'error',
            trans('alert.notexisting_group')
        );
    }

    public function getApply($id)
    {
        if($id > 0){
            $group = $this->_groupService->Get($id);
            if(!$this->_groupService->HasApplication($this->_currentUser->GetId(), $id))
            {
                return View::make('group.apply')
                    ->with(array('group' => $group,
                    'isMember' => $this->_groupService->IsInGroup($this->_currentUser->GetId(), $id)));
            }
            
            return Redirect::to('group/list')->with(
                'error',
                trans('alert.already_applied')
            );
        }

        return Redirect::to('/')->with(
            'error',
            trans('alert.notexisting_group')
        );
    }
    
    private function formatNotificationMessage($type, $user, $date, $group)
    {
        if($type > 0 && $type < 9)
        {
            if($type == 3 || $type == 4)
            {
                return '<a href="'.\    URL::to('group/polls/' . $group).'">'
                . \App\Helpers\DateHelper::sqlDateToStringHuman($date).' - '
                .trans('notifications.type_' . $type, array('user' => htmlentities($user)))
                .'</a>';
        
            }
            return \App\Helpers\DateHelper::sqlDateToStringHuman($date).' - '.trans('notifications.type_' . $type, array('user' => $user));
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
            return Redirect::to('/group/view/' . Input::get('id_group'))->with(
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
            if($this->_groupService->IsInGroup($this->_currentUser->GetId(), Input::get('id_group')))
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
    
    public function getGames($id, $action = null, $game = 0)
    {
        if($id > 0)
        {
            $group = $this->_groupService->Get($id);
            if($group == null)
            {
                return Redirect::to('/')->with(
                        'error',
                        trans('alert.notexisting_group')
                    );
            }
            
            if($action == 'suggest' && $game > 0)
            {
                $this->_groupService->SuggestGameForGroup($id, $game);
                return Redirect::to('group/polls/' . $id);
            }
        
            $games = $this->_groupService->GetGroupGames($id, 7);

            return view('group.games', array('games' => $games));
        }   
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
                return Redirect::to('group/games/'. $id);
        } 
    }
    
    public function getPolls($id, $action = null)
    {
        if($id > 0)
        {
            if($action != null)
            {
                if($action == 'accept')
                {
                    $this->_pollService->AddVote($id, \App\Models\Types\VoteTypes::YES);
                }
                else if($action == 'refuse')
                {
                    $this->_pollService->AddVote($id, \App\Models\Types\VoteTypes::NO);
                }
                
                return Redirect::to('group/polls/' . $id);
            }

            $actives = $this->_pollService->GetActivePollsForGroup($id);
            $expired = $this->_pollService->GetExpiredPollsForGroup($id);
            
            $activesForView = array();
            foreach ($actives as $a)
            {
                $activesForView[] = new \App\Models\ViewModels\PollViewModel($a->id, 
                        $a->type, 
                        $a->created_at,
                        $this->_pollService->GetPercents($a->id),
                        $this->_pollService->GetUserVote($a->id),
                        new \App\Models\Services\UserBrief($a->uid, $a->pseudo));
            }
            
            $expiredForView = array();
            foreach ($expired as $a)
            {
                $expiredForView[] = new \App\Models\ViewModels\PollViewModel($a->id, 
                        $a->type, 
                        $a->created_at,
                        $this->_pollService->GetPercents($a->id),
                        $this->_pollService->GetUserVote($a->id),
                        new \App\Models\Services\UserBrief($a->uid, $a->pseudo));
            }
            
            return View::make('group/polls')
                    ->with(array('model' => 
                        new \App\Models\ViewModels\PollsListViewModel(
                                $this->_groupService->Get($id),
                                $activesForView, 
                                $expiredForView)));
        }
        
        return Redirect::to('/')->with(
            'error',
            trans('alert.notexisting_group')
        );
    }
}
