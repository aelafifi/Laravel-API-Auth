<?php

namespace ElMag\AuthAPI;

use Closure;
use Illuminate\Http\Request;

class AuthApiMiddleware
{
    /**
     * Authenticate user using AuthAPI
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = AuthApiTokenManager::getRequestedUser();

        if (is_object($user)) {
            return $next($request);
        }

        switch ($user) {
            case -1:
                throw new AuthApiException(401, 'UnAuthorized.');
            case -2:
                throw new AuthApiException(401, 'Token Not Found.');
            case -3:
                throw new AuthApiException(401, 'Token Expired.');
            default:
                throw new AuthApiException(500, 'Unknown Exception.');
        }
    }
}
