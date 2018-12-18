<?php

use ElMag\AuthApi\JsonStoreTokenManager;

if (!function_exists('api_user')) {
    function api_user()
    {
        $user = JsonStoreTokenManager::getRequestedUser();

        if (is_int($user) && $user < 0) {
            return null;
        }

        return $user;
    }
}