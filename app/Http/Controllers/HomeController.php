<?php namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Services\GameService;
use App\Services\BetService;
use App\Services\UserService;
use App\Services\SportService;
use \View;
use \Redirect;
use \Input;
use \Validator;
use \Session;
use \Auth;


class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    private $_gameService;
    private $_betService;
    private $_userService;
    private $_sportService;

    public function __construct(
            GameService $gameService, 
            BetService $betService, 
            UserService $userService,
            SportService $sportService)
    {
        $this->_gameService = $gameService;
        $this->_betService = $betService;
        $this->_userService = $userService;
        $this->_sportService = $sportService;
    }

    public function getIndex()
    {
        if(Auth::check())
        {
            $games = $this->_gameService->GetNext7DaysGames();
            $bets = $this->_betService->GetCurrentUserBetsForNext7Days();
            $rates = array();
            
            
            foreach ($games as $g)
            {
                $r = $this->_betService->GetRates($g->id);
                $rates[$g->id] = $r;
            }
            
            return View::make('home/index', array('games' => $games, 
                'bets' => $bets,
                'rates' => $rates));
        }

        return View::make('home/index');
    }

    public function postIndex()
    {
        if(Auth::check() && !empty(Input::get('games')))
        {
            $bets = $this->_betService->GetUserPendingBets();

            foreach (Input::get('games') as $id => $score) 
            {
                if($score['state']!=='') 
                {
                    $g = $this->_gameService->Get($id);
                    if(DateHelper::getTimestampFromSqlDate($g->date) < time() || array_key_exists($g->id, $bets) ||
                        DateHelper::getTimestampFromSqlDate($g->date) > time() + (60*60*24*7))
                   {
                       continue;   
                   }
                }
            }

            $this->_betService->Create($id, $score['state']);
            return Redirect::to('/')->with('success',trans('general.betstaken'));
        }

        return Redirect::to('/');
    }

    public function getLogin()
    {
        if(Auth::check())
        {
            return Redirect::to('/');
        }

        return View::make('auth/login');
    }

    public function postLogin()
    {
        if(Auth::check())
        {
            return Redirect::to('/');
        }

        $email = Input::get('email');
        $password = Input::get('password');
        $rem = false;
        
        if(Input::get('remember') === 'on')
        {
            $rem = true;
        }

        if ($this->_userService->AttemptLogin($email, $password, $rem))
        {
            return Redirect::intended('/')->with('success', trans('alert.suclogin'));
        }

        return Redirect::to('login')->with('error', trans('alert.faillogin'));
    }

    public function getLogout()
    {
        $this->_userService->LogOut();
        return Redirect::to('/')->with('success', trans('alert.suclogout'));
    }

    public function getRegister()
    {
        if(Auth::check())
        {
            return Redirect::to('/');
        }

        return View::make('user/register');
    }

    public function postRegister()
    {
        if(Auth::check())
        {
            return Redirect::to('/');
        }

        $validator = Validator::make(
            Input::all(),
            array(
                'name' => array('required', 'min:3', 'alpha_dash'),
                'display' => array('required', 'min:3', 'alpha_spaces'),
                'email' => array('required', 'email'),
                'password' => array('required', 'confirmed','min:8')
            )
        );

        if ($validator->passes())
        {
            if(!$this->_userService->UserExists(Input::get('name'))
                    && !$this->_userService->EmailExists(Input::get('email')))
            {
                $id = $this->_userService->CreateUser(
                        Input::get('name'), 
                    Input::get('email'), 
                    Input::get('display'), 
                    Input::get('password'),
                    Input::get('password_confirmation'));
                if($id > 0) 
                {
                    return Redirect::to('/login')->with(
                        'success',
                        trans('alert.welcome').', '. Input::get('display') . '!'
                    );
                }
                
                return Redirect::to('register')->with(
                    'error',
                    trans('alert.unexpected_error')
                )->withInput();
            }
            
            return Redirect::to('register')->with(
                    'error',
                    trans('alert.somethingexists')
                )->withInput();    
        }

        return Redirect::to('register')->with(
                'error',
                trans('alert.correcterrors')
            )->withErrors($validator)->withInput();
    }

    public function getRecover($token = null)
    {
        if(Auth::check())
        {
            $token = $this->_userService->GetRecoverToken(Auth::user()->email);
            Session::put('token', $token);

            return View::make('user/reset');
        }

        if($token)
        {
            $recover = $this->_userService->GetFromToken($token);

            if(!$recover)
            {
                return Redirect::to('recover')->with('error',trans('alert.reseterror'));
            }

            $user = $this->_userService->GetUserById($recover->users_id);
            if(!$user)
            {
                return Redirect::to('recover')->with('error',trans('alert.reseterror'));
            }

            $token2 = $this->_userService->GetRecoverToken($user->email);
            if($token === $token2)
            {
                Session::put('token', $token);
                return View::make('user/reset');
            }

            return Redirect::to('recover')->with('error',trans('alert.reseterror'));
        }

        return View::make('user/reminder');
    }

    public function postRecover()
    {
        if(Auth::check())
        {
            return Redirect::to('/');
        }

        $validator = Validator::make(
            Input::all(),
            array(
                'email' => 'Email|Required'
            )
        );
        if($validator->passes())
        {
            $user = $this->_userService->GetUserFromEmail(Input::get('email'));

            if($user)
            {
                $token = $this->_userService->CreateRecoverTokenForUser($user->id);
                
                Mail::send('emails/reminder', array('display'=>$user->display, 'token' => $token), function($message) use ($user)
                {
                    $message->to($user->email, $user->display)->subject(trans('reminders.subject'));
                });
            }

            return Redirect::to('/')->with(array('success' => trans('alert.sucreminderemail')));
        }

        return Redirect::to('recover')->with('error',trans('alert.invalidemail'));
    }

    public function postReset()
    {
        if(Auth::check() && Session::has('token'))
        {
            $validator = Validator::make(
                Input::all(),
                array(
                    'password' => array('required', 'confirmed', 'min:8')
                )
            );
            if($validator->passes())
            {
                $this->_userService->ChangeUserPassword(Input::get('password'));
                
                return Redirect::to('profile')->with(
                    'success',
                    trans('alert.sucresetlogged')
                );
            }

            return Redirect::to('recover')->with(
                    'error',
                    trans('alert.correcterrors')
                )->withErrors($validator)->withInput();

        }
        else if(Auth::check())
        {
            return Redirect::to('recover');
        }

        if(Session::has('token'))
        {
            $validator = Validator::make(
                Input::all(),
                array(
                    'password' => array('required', 'confirmed', 'min:8')
                )
            );
            if($validator->passes())
            {
                try
                {
                    $this->_userService->GetUserFromRecoverToken(Session::get('token'));
                    $this->_userService->ChangeUserPasswordReset($userId, Input::get('password'));

                    return Redirect::to('login')->with(
                            'success',
                            trans('alert.sucreset')
                        );
                }
                catch(Exception $e)
                {
                    return Redirect::to('recover')->with(
                        'error',
                        trans('alert.reseterror')
                    );
                }
            }

            return Redirect::to('recover/'.Session::get('token'))->with(
                    'error',
                    trans('alert.correcterrors')
                )->withErrors($validator)->withInput();

        }

        return Redirect::to('/recover')->with(
                'error',
                trans('alert.reseterror')
            );
    }

    public function getProfile($user)
    {
        if(Auth::check())
        {
            return View::make('user/myprofile', array('user'=>Auth::user()));
        }

        return Redirect::to('/login')->with('error', trans('alert.needlogin'));
    }

    public function getLadder()
    {
        $pages[] = array('name' => 'default', 'users' => $this->_betService->GetTopBettorBetween(0, 10));
        $pages[] = array('name' => '10', 'users' => $this->_betService->GetTopBettorBetween(10, 25));
        $pages[] = array('name' => '25', 'users' => $this->_betService->GetTopBettorBetween(25, 50));
        $pages[] = array('name' => '50', 'users' => $this->_betService->GetTopBettorBetween(50, 100));
        $pages[] = array('name' => '100', 'users' => $this->_betService->GetTopBettorBetween(100, 500));
        $pages[] = array('name' => '500', 'users' => $this->_betService->GetTopBettorBetween(500, 1000));
        $pages[] = array('name' => '1000', 'users' => $this->_betService->GetTopBettorBetween(1000, 10000));
        $pages[] = array('name' => '10000', 'users' => $this->_betService->GetTopBettorSuperior(10000));

        return View::make('home/top', array('pages' => $pages));
    }

    public function getSuggest()
    {
        $suggestions = $this->_gameService->GetUserSuggestions();
        return View::make('home/suggest', array('suggestions' => $suggestions));
    }

    public function postSuggest()
    {
        $validator = Validator::make(
            Input::all(),
            array(
                'team1' => array('required', 'min:3', 'alpha_spaces'),
                'team2' => array('required', 'min:3', 'alpha_spaces'),
                'date' => array('required', 'date_format:"Y-m-d"'),
                'time' => array('required', 'date_format:"H:i"'),
                'sport' => array('required'),
            )
        );

        if ($validator->passes()) 
        {
            if($this->_sportService->SportExists(Input::get('sport')))
            {
                $this->_gameService->Suggest(Input::get('sport'), 
                    Input::get('team1'), 
                    Input::get('team2'), 
                    Input::get('event'),
                    Input::get('date').' '.Input::get('time').':00');

                return Redirect::to('/suggest')->with('success',trans('general.suggestdone'));
            }
            
            return Redirect::to('/suggest')->with(
                'error',
                trans('alert.correcterrors')
            )->withInput();
        }

        return Redirect::to('/suggest')->with(
                'error',
                trans('alert.correcterrors')
            )->withErrors($validator)->withInput();
    }
    
    public function getBet($game, $state)
    {
        if(Auth::check())
        {
            $this->_betService->Create($game, $state);
            return Redirect::to('/');
        }
    
        return Redirect::to('/login')->with('error', trans('alert.needlogin'));
    }
}
