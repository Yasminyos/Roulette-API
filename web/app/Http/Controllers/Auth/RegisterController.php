<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Auth\DTO\UserRegisterDTO;
use App\Domains\Auth\Managers\AuthManager;
use App\Domains\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegister;

class RegisterController extends Controller
{
    /** @var \App\Domains\Auth\Managers\AuthManager */
    private $authManager;
    
    public function __construct(
        AuthManager $authManager
    ) {
        $this->authManager = $authManager;
    }
    
    /**
     * @param UserRegister $userRegister
     * @return User
     */
    public function register(UserRegister $userRegister): User
    {
        $DTO = new UserRegisterDTO($userRegister->toArray());
        
        return $this->authManager->register($DTO);
    }
}
