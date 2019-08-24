<?php

namespace App\Domains\Auth\Managers;

use App\Domains\Auth\DTO\TokenDTO;
use App\Domains\Auth\DTO\UserLoginDTO;
use App\Domains\Auth\DTO\UserRegisterDTO;
use App\Domains\Auth\Exceptions\InvalidPasswordException;
use App\Domains\Auth\Repositories\ApiTokenRepositoryInterface;
use App\Domains\User\Managers\UserManager;
use App\Domains\User\Models\User;
use App\Tools\Exception\DtoException;
use App\Tools\Hash\HashManagerInterface;
use App\Tools\Repositories\BaseRepositoryInterface;
use Auth;

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
     * @throws DtoException
     */
    public function register(UserRegisterDTO $DTO): TokenDTO
    {
        if (!$DTO->validate()) {
            throw new DtoException($DTO);
        }
        
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
     * @throws DtoException
     */
    public function login(UserLoginDTO $DTO): TokenDTO
    {
        if (!$DTO->validate()) {
            throw new DtoException($DTO);
        }
        
        $user = $this->userManager->getWhereEmail($DTO->email);
        $validPassword = $this->hashManager->checkEquals($DTO->password, $user->password);
        
        if (!$validPassword) {
            throw new InvalidPasswordException();
        }
        
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
