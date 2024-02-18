<?php

namespace App\Domains\Course\Models;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class CyberQTokens extends Model
{

    protected $table = 'cyberq_tokens';

    protected $fillable = ['access_token', 'expired_at', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
