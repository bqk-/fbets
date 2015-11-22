<?php namespace App\Http\Controllers;

use App\Services\UserService;
use \View;
use \Redirect;
use \DB;
use \Artisan;
use \Config;
use \Input;
use \Validator;
use \Session;
use \Auth;

class UserController extends Controller
{
    private $_userService;

    public function __construct(UserService $userService)
    {
        if(!Auth::check())
        {
            return Redirect::to('/');
        }

        $this->_userService = $userService;
        return true;
    }

    public function getIndex($name = null)
    {
        if($name == null || $name == Auth::user()->pseudo)
        {
            return Redirect::to('/profile');
        }

        $user = $this->_userService->GetUserInfoByPseudo($name);
        return View::make('user/profile', array('user'=>$user));
    }


}