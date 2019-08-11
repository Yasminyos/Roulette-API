<?php

namespace App\Domains\Auth\Repositories;

interface ApiTokenRepositoryInterface
{
    public function getToken(int $userId): ?string;
    
    public function getUserIdFromToken(string $token): ?int;
    
    public function createToken(int $userId): string;
    
    public function deleteToken(int $userId): bool;
}
