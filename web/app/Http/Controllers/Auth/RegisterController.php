<?php

namespace App\Http\Controllers\Auth;

use App\Domains\User\DTO\UserCreateDTO;
use App\Http\Requests\UserRegister;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register(UserRegister $userRegister)
    {
        $DTO = new UserCreateDTO();
        $DTO->email = $userRegister->get('email');
        $DTO->password = $userRegister->get('password');
        
        return $DTO->toArray();
    }
}
