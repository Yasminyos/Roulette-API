<?php

namespace App\Http\Middleware;

use App\Domains\Auth\Exceptions\UnauthorizedException;
use App\Domains\Auth\Managers\ApiTokenManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthToken
{
    /** @var ApiTokenManager */
    private $apiTokenManager;
    
    public function __construct(
        ApiTokenManager $apiTokenManager
    ) {
        $this->apiTokenManager = $apiTokenManager;
    }
    
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws Throwable
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('auth_token');
        throw_if($token === null, new UnauthorizedException('Auth token not found'));
        
        $userId = $this->apiTokenManager->getUserIdFromToken($token);
        throw_if($userId === null, new UnauthorizedException('Auth token invalid'));
        
        Auth::loginUsingId($userId);
        
        return $next($request);
    }
}
