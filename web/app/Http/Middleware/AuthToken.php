<?php

namespace App\Http\Middleware;

use App\Domains\Auth\Exceptions\UnauthorizedException;
use App\Domains\Auth\Managers\ApiTokenManager;
use Closure;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

class AuthToken
{
    /** @var ApiTokenManager */
    private $apiTokenManager;
    
    /** @var StatefulGuard */
    private $guard;
    
    public function __construct(
        ApiTokenManager $apiTokenManager,
        Factory $guard
    ) {
        $this->apiTokenManager = $apiTokenManager;
        $this->guard = $guard;
    }
    
    public function handle(Request $request, Closure $next)
    {
        $token = $this->getToken($request);
        $userId = $this->getUserIdByToken($token);
        
        $this->guard->loginUsingId($userId);
        
        return $next($request);
    }
    
    private function getToken(Request $request): string
    {
        $token = $request->header('auth_token');
        
        if ($token === null) {
            throw new UnauthorizedException('Auth token not found');
        }
        
        return $token;
    }
    
    private function getUserIdByToken(string $token): int
    {
        $userId = $this->apiTokenManager->getUserIdFromToken($token);
        
        if ($userId === null) {
            throw new UnauthorizedException('Auth token invalid');
        }
        
        return $userId;
    }
}
