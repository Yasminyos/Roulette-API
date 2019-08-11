<?php

namespace App\Domains\Auth\Repositories;

use App\Domains\Auth\Criteria\TokenCriteria;
use App\Domains\Auth\Models\ApiToken;
use App\Domains\User\Criteria\UserIdCriteria;
use App\Domains\User\Managers\UserManager;
use App\Tools\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Str;
use Throwable;

class ApiTokenDbRepository implements ApiTokenRepositoryInterface
{
    /** @var BaseRepositoryInterface */
    private $apiTokenRepository;
    
    /** @var UserManager */
    private $userManager;
    
    public function __construct(
        BaseRepositoryInterface $baseRepository,
        UserManager $userManager
    ) {
        $this->apiTokenRepository = $baseRepository->setModel(ApiToken::class);
        $this->userManager = $userManager;
    }
    
    public function getToken(int $userId): ?string
    {
        /** @var ApiToken $apiToken */
        $apiToken = $this->apiTokenRepository->findOne(new UserIdCriteria($userId));
        
        return $apiToken !== null ? $apiToken->token : null;
    }
    
    public function getUserIdFromToken(string $token): ?int
    {
        /** @var ApiToken $apiToken */
        $apiToken = $this->apiTokenRepository->findOne(new TokenCriteria($token));
        
        return $apiToken !== null ? $apiToken->user_id : null;
    }
    
    /**
     * @param  int  $userId
     * @return string
     * @throws Throwable
     */
    public function createToken(int $userId): string
    {
        $this->userManager->get($userId);
        
        $apiToken = $this->apiTokenRepository->findOne(new UserIdCriteria($userId));
        
        if ($apiToken === null) {
            $apiToken = new ApiToken();
        }
        
        $apiToken->user_id = $userId;
        $apiToken->token = $this->generateToken();
        
        $this->apiTokenRepository->save($apiToken);
        
        return $apiToken->token;
    }
    
    private function generateToken(): string
    {
        return Str::random(32);
    }
    
    /**
     * @param  int  $userId
     * @return bool
     */
    public function deleteToken(int $userId): bool
    {
        /** @var ApiToken $apiToken */
        $apiToken = $this->apiTokenRepository->findOne(new UserIdCriteria($userId));
        $this->apiTokenRepository->delete($apiToken);
        
        return true;
    }
}
