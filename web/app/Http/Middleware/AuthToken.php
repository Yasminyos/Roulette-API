<?php

namespace App\Http\Middleware;

use App\Domains\Auth\Exceptions\UnauthorizedException;
use App\Domains\User\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthToken
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws Throwable
     * @throws \App\Domains\Auth\Exceptions\UnauthorizedException
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('auth_token');
        throw_if($token === null, new UnauthorizedException('Auth token not found'));
        
        $user = User::whereApiToken($token)->first();
        throw_if($user === null, new UnauthorizedException('Auth token invalid'));
        
        Auth::login($user);
        
        return $next($request);
    }
}
