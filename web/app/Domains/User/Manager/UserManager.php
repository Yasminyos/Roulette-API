<?php


namespace App\Domanins\User\Manager;


use App\Domains\User\DTO\UserCreateDTO;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserManager
{
    public function create(UserCreateDTO $DTO)
    {
        $DTO->validateThrowException();
        
        $user = new User();
        $user->email = $DTO->email;
    }
}
