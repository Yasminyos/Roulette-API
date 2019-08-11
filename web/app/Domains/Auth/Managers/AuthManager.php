<?php

namespace App\Domains\Auth\Managers;

use App\Domains\Auth\DTO\TokenDTO;
use App\Domains\Auth\DTO\UserLoginDTO;
use App\Domains\Auth\DTO\UserRegisterDTO;
use App\Domains\Auth\Exceptions\InvalidPasswordException;
use App\Domains\Auth\Repositories\ApiTokenRepositoryInterface;
use App\Domains\User\Managers\UserManager;
use App\Domains\User\Models\User;
use App\Tools\Hash\HashManagerInterface;
use App\Tools\Repositories\BaseRepositoryInterface;
use Auth;
use Throwable;

class AuthManager
{
    /** @var UserManager */
    private $userManager;
    
    /** @var HashManagerInterface */
    private $hashManager;
    
    /** @var BaseRepositoryInterface */
    private $userRepository;
    
    /** @var ApiTokenRepositoryInterface */
    private $apiTokenRepository;
    
    public function __construct(
        UserManager $userManager,
        HashManagerInterface $hashManager,
        BaseRepositoryInterface $baseRepository,
        ApiTokenRepositoryInterface $apiTokenRepository
    ) {
        $this->userManager = $userManager;
        $this->hashManager = $hashManager;
        $this->userRepository = $baseRepository->setModel(User::class);
        $this->apiTokenRepository = $apiTokenRepository;
    }
    
    /**
     * @param  UserRegisterDTO  $DTO
     * @return TokenDTO
     */
    public function register(UserRegisterDTO $DTO): TokenDTO
    {
        $DTO->validateThrowException();
        
        $user = new User();
        $user->email = $DTO->email;
        $user->password = $this->hashManager->createHash($DTO->password);
        
        $this->userRepository->save($user);
        $user->refresh();
        
        $token = $this->apiTokenRepository->createToken($user->id);
        
        return new TokenDTO([
            'user_id' => $user->id,
            'token'   => $token
        ]);
    }
    
    /**
     * @param  UserLoginDTO  $DTO
     * @return TokenDTO
     * @throws Throwable
     */
    public function login(UserLoginDTO $DTO): TokenDTO
    {
        $DTO->validateThrowException();
        
        $user = $this->userManager->getWhereEmail($DTO->email);
        $validPassword = $this->hashManager->checkEquals($DTO->password, $user->password);
        throw_if(!$validPassword, new InvalidPasswordException());
        
        $token = $this->apiTokenRepository->getToken($user->id);
        
        if ($token === null) {
            $token = $this->apiTokenRepository->createToken($user->id);
        }
        
        return new TokenDTO([
            'user_id' => $user->id,
            'token'   => $token
        ]);
    }
    
    public function logout(): string
    {
        /** @var User $user */
        $user = Auth::user();
        
        return $this->apiTokenRepository->deleteToken($user->id);
    }
}
