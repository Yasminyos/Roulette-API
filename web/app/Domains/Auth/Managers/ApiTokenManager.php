<?php

namespace App\Domains\Auth\Managers;

use App\Domains\Auth\Repositories\ApiTokenRepositoryInterface;

class ApiTokenManager
{
    /** @var ApiTokenRepositoryInterface */
    private $apiTokenRepository;
    
    public function __construct(
        ApiTokenRepositoryInterface $apiTokenRepository
    ) {
        $this->apiTokenRepository = $apiTokenRepository;
    }
    
    public function create(int $userId): string
    {
        return $this->apiTokenRepository->createToken($userId);
    }
    
    public function get(int $userId): ?string
    {
        return $this->apiTokenRepository->getToken($userId);
    }
    
    public function getUserIdFromToken(string $token): ?int
    {
        return $this->apiTokenRepository->getUserIdFromToken($token);
    }
    
    public function delete(int $userId): bool
    {
        return $this->apiTokenRepository->deleteToken($userId);
    }
}
