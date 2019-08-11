<?php

namespace App\Domains\Auth\Managers;

use App\Domains\Auth\DTO\UserLoginDTO;
use App\Domains\Auth\DTO\UserRegisterDTO;
use App\Domains\Auth\Exceptions\InvalidPasswordException;
use App\Domains\User\Managers\UserManager;
use App\Domains\User\Models\User;
use App\Tools\Hash\HashManagerInterface;
use App\Tools\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Str;
use Throwable;

class AuthManager
{
    /** @var UserManager */
    private $userManager;
    
    /** @var HashManagerInterface */
    private $hashManager;
    
    /** @var BaseRepositoryInterface */
    private $userRepository;
    
    public function __construct(
        UserManager $userManager,
        HashManagerInterface $hashManager,
        BaseRepositoryInterface $baseRepository
    ) {
        $this->userManager = $userManager;
        $this->hashManager = $hashManager;
        $this->userRepository = $baseRepository->setModel(User::class);
    }
    
    /**
     * @param  UserRegisterDTO  $DTO
     * @return User
     */
    public function register(UserRegisterDTO $DTO): User
    {
        $DTO->validateThrowException();
        
        $user = new User();
        $user->email = $DTO->email;
        $user->password = $this->hashManager->createHash($DTO->password);
        $user->api_token = Str::random(60);
        
        $this->userRepository->save($user);
        $user->refresh();
        
        return $user;
    }
    
    /**
     * @param  UserLoginDTO  $DTO
     * @return User
     * @throws Throwable
     * @throws InvalidPasswordException
     */
    public function login(UserLoginDTO $DTO): User
    {
        $DTO->validateThrowException();
        
        $user = $this->userManager->getWhereEmail($DTO->email);
        $validPassword = $this->hashManager->checkEquals($DTO->password, $user->password);
        
        throw_if(!$validPassword, new InvalidPasswordException());
        
        return $user;
    }
}
