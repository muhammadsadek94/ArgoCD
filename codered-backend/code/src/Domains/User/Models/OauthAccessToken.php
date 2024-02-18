<?php

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    public function scopeActive($query)
    {
        $query->where('revoked', 0)
            ->where('expires_at', '>', now());
    }
}