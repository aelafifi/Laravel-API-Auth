<?php

namespace ElMag\AuthApi;

use Closure;
use Illuminate\Http\Request;

class AuthApiMiddleware
{
    /**
     * Authenticate user using AuthApi
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = JsonStoreTokenManager::getRequestedUser();

        if (is_object($user)) {
            return $next($request);
        }

        switch ($user) {
            case -1:
                throw new JsonStoreException(401, 'UnAuthorized.');
            case -2:
                throw new JsonStoreException(401, 'Token Not Found.');
            case -3:
                throw new JsonStoreException(401, 'Token Expired.');
            default:
                throw new JsonStoreException(500, 'Unknown Exception.');
        }
    }
}
