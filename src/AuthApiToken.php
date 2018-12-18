<?php

namespace ElMag\AuthApi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AuthApiToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
    ];

    protected $dates = [
        'last_access',
    ];

    protected $casts = [
        'is_expired' => 'boolean',
    ];

    public function getToken()
    {
        return str_random(60);
    }

    public function user()
    {
        return $this->belongsTo(JsonStoreTokenManager::getUserClass(), 'user_id');
    }

    public function access()
    {
        if ($this->isValid()) {
            $this->last_access = Carbon::now();
            return $this->save();
        }
    }

    public function scopeWhereValid($query)
    {
        return $query->where('is_expired', 0);
    }

    public function scopeWhereInvalid($query)
    {
        return $query->where('is_expired', 1);
    }

    public function isValid()
    {
        return !$this->is_expired;
    }
}
