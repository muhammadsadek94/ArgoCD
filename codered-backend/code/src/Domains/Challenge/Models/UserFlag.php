<?php

namespace App\Domains\Challenge\Models;

use INTCore\OneARTFoundation\Model;

class UserFlag extends Model
{
    protected $fillable = [
        'challenge_id', 'user_id', 'guest_id', 'competition_id', 'event_id', 'flag_id', 'total_time', 'time_taken', 'user_score'
    ];
}
