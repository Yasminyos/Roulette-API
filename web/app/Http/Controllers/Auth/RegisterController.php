<?php

namespace App\Http\Controllers\Auth;

use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\Manager\UserManager;
use App\Http\Requests\UserRegister;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /** @var UserManager */
    private $userManager;
    
    public function __construct(
        UserManager $userManager
    ) {
        $this->userManager = $userManager;
    }
    
    /**
     * @param UserRegister $userRegister
     * @return UserManager
     */
    public function register(UserRegister $userRegister): UserManager
    {
        $DTO = new UserCreateDTO();
        $DTO->email = $userRegister->get('email');
        $DTO->password = $userRegister->get('password');
        
        return $this->userManager;
    }
}
