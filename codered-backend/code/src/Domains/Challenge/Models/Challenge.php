<?php

namespace App\Domains\Challenge\Models;

use INTCore\OneARTFoundation\Model;

class Challenge extends Model
{
    protected $fillable = [
        'name', 'competition_id', 'slug', 'image', 'activation', 'duration', 'end_date', 'description', 'competition_scenario', 'guidelines', 'tags', 'flags'
    ];

    protected $casts = [
        'tags' => 'array',
        'flags' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('activation', 1)->where('end_date', '>=', now());
    }

    public function challengeCompetitions()
    {
        return $this->hasMany(UserCompetition::class, 'competition_id', 'competition_id');
    }

    public function challengeFlags()
    {
        return $this->hasMany(UserFlag::class, 'competition_id', 'competition_id');
    }
}
