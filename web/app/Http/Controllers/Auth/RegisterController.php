<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Auth\DTO\TokenDTO;
use App\Domains\Auth\DTO\UserRegisterDTO;
use App\Domains\Auth\Managers\AuthManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegister;
use App\Tools\Exception\DtoException;
use Illuminate\Contracts\Container\BindingResolutionException;

class RegisterController extends Controller
{
    /** @var AuthManager */
    private $authManager;
    
    public function __construct(
        AuthManager $authManager
    ) {
        $this->authManager = $authManager;
    }
    
    /**
     * @param  UserRegister  $userRegister
     * @return TokenDTO
     * @throws DtoException
     * @throws BindingResolutionException
     */
    public function register(UserRegister $userRegister): TokenDTO
    {
        $DTO = new UserRegisterDTO($userRegister->toArray());
        
        return $this->authManager->register($DTO);
    }
}
