<?php

use ElMag\AuthAPI\AuthApiTokenManager;

if (!function_exists('api_user')) {
    function api_user()
    {
        $user = AuthApiTokenManager::getRequestedUser();

        if (is_int($user) && $user < 0) {
            return null;
        }

        return $user;
    }
}