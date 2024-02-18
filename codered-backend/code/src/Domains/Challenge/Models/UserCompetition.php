<?php

namespace App\Domains\Challenge\Models;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class UserCompetition extends Model
{
    protected $fillable = [
        'challenge_id', 'user_id', 'guest_id', 'competition_id', 'event_id', 'is_lab_launched', 'is_lab_completed', 'started_at', 'completed_at', 'total_score', 'exam_score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guest()
    {
        return $this->belongsTo(CompetitionGuest::class);
    }

    public function flags()
    {
        return $this->hasMany(UserFlag::class, 'competition_id', 'competition_id');
    }
}
