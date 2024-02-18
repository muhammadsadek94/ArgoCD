<?php

namespace App\Domains\Course\Models;

use App\Domains\User\Models\User;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Model;

class FinalAssessmentTimer extends Model
{
    protected $fillable = ['user_id', 'course_id', 'started_at', 'ended_at', 'start_time', 'end_time'];
    protected $appends = [
        'time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }

    public function getTimeAttribute()
    {
        $ended_at = $this->ended_at == null ? Carbon::now() : $this->ended_at;
        return Carbon::parse( $ended_at)->diffInSeconds($this->started_at);
    }
}
