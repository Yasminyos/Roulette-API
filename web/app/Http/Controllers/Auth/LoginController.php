<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Auth\DTO\UserLoginDTO;
use App\Domains\Auth\Managers\AuthManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLogin;
use Throwable;

class LoginController extends Controller
{
    /** @var AuthManager */
    private $authManager;
    
    public function __construct(
        AuthManager $authManager
    ) {
        $this->authManager = $authManager;
    }
    
    /**
     * @param  UserLogin  $userLogin
     * @return string
     * @throws Throwable
     */
    public function login(UserLogin $userLogin): string
    {
        $DTO = new UserLoginDTO($userLogin->toArray());
        
        return $this->authManager->login($DTO);
    }
}
