<?php

namespace ElMag\AuthAPI;

use Illuminate\Support\Carbon;

trait AuthApiTokenable
{
    public function getValidToken()
    {
        AuthApiTokenManager::boot();
        return $this->tokens()->whereValid()->first();
    }

    public function tokens()
    {
        return $this->hasMany(AuthApiToken::class, 'user_id');
    }

    public function getTokenOrCreate()
    {
        AuthApiTokenManager::boot();

        /** @var AuthApiToken $model */
        $model = $this
            ->tokens()
            ->whereValid()
            ->firstOrNew([]);

        if (!$model->exists) {
            $model->token = $model->getToken();
        }

        $model->last_access = Carbon::now();
        $model->save();

        return $model;
    }

}
