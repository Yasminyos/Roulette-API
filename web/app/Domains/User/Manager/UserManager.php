<?php

namespace App\Domains\User\Manager;

use App\Domains\User\DTO\UserCreateDTO;
use App\Tools\Hash\HashManagerInterface;
use App\User;

class UserManager
{
    /** @var HashManagerInterface */
    private $hashManager;
    
    public function __construct(
        HashManagerInterface $hashManager
    ) {
        $this->hashManager = $hashManager;
    }
    
    public function create(UserCreateDTO $DTO): User
    {
        $DTO->validateThrowException();
        
        $user = new User();
        $user->email = $DTO->email;
        $user->password = $this->hashManager->createHash($DTO->password);
        
        $user->save();
        $user->refresh();
        
        return $user;
    }
}
