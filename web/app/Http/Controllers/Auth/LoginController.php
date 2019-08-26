<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Auth\DTO\TokenDTO;
use App\Domains\Auth\DTO\UserLoginDTO;
use App\Domains\Auth\Managers\AuthManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLogin;
use App\Tools\Exception\DtoException;
use Illuminate\Contracts\Container\BindingResolutionException;
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
     * @return TokenDTO
     * @throws DtoException
     * @throws BindingResolutionException
     */
    public function login(UserLogin $userLogin): TokenDTO
    {
        $DTO = new UserLoginDTO($userLogin->toArray());
        
        return $this->authManager->login($DTO);
    }
    
    public function logout(): void
    {
        $this->authManager->logout();
    }
}
