<?php

namespace App\Domains\Course\Models;

use App\Domains\User\Models\User;
use App\Domains\User\Models\UserSubscription;
use Eloquent;

class CourseEnrollment extends Eloquent
{
    protected $table = 'course_enrollment';

    protected $fillable = ['course_id', 'user_id', 'subscription_type', 'expired_at', 'created_at', 'weekly_target', 'selected_days', 'week_start_date', 'week_end_date', 'user_subscription_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user_subscription()
    {
        return $this->belongsTo(UserSubscription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
