<?php

namespace ElMag\AuthApi;

use Illuminate\Support\Carbon;

class AuthApiTokenManager
{
    const TOKEN_NOT_SET = -1;
    const TOKEN_NOT_EXISTS = -2;
    const TOKEN_EXPIRED = -3;

    protected static $booted = false;
    protected static $user_class = \App\User::class;
    protected static $validity_days = 30;

    public static function boot()
    {
        if (!static::$booted) {
            static::$booted = true;

            $expiration_date = Carbon::now()->subDays(static::$validity_days);
            (new JsonStore)
                ->newQuery()
                ->where('last_access', '<=', $expiration_date)
                ->update([
                    'is_expired' => true,
                ]);
        }
    }

    public static function getUserClass(): string
    {
        return self::$user_class;
    }

    public static function getRequestedUser()
    {
        static $requested_user;

        if ($requested_user === null) {
            static::boot();

            $header_token = request()->header('X-Auth-Token');
            $query_token = request()->get('_auth_token');
            $token = $header_token ?? $query_token;

            if ($token === null) {
                return static::TOKEN_NOT_SET;
            }

            $tokenRecord = (new JsonStore)
                ->newQuery()
                ->where('token', $token)
                ->first();

            if (!$tokenRecord) {
                return static::TOKEN_NOT_EXISTS;
            }

            if (!$tokenRecord->isValid()) {
                return static::TOKEN_EXPIRED;
            }

            $tokenRecord->access();
            $requested_user = $tokenRecord->user;
        }

        return $requested_user;
    }
}
