<?php

namespace ElMag\AuthApi;

use Illuminate\Support\Carbon;

trait AuthApiTokenable
{
    public function getValidToken()
    {
        JsonStoreTokenManager::boot();
        return $this->tokens()->whereValid()->first();
    }

    public function tokens()
    {
        return $this->hasMany(JsonStore::class, 'user_id');
    }

    public function getTokenOrCreate()
    {
        JsonStoreTokenManager::boot();

        /** @var JsonStore $model */
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
