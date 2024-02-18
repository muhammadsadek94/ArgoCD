<?php

namespace App\Domains\User\Models;

use App\Domains\Course\Events\Learnpath\CheckCompletedCoursesEvent;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\User\Enum\SubscribeStatus;
use INTCore\OneARTFoundation\Model;

class UserSubscription extends Model
{
    protected $fillable = ['status', 'expired_at', 'subscription_id', 'user_id', 'package_id', 'is_installment', 'paid_installment_count'];

    public static function boot()
    {
        parent::boot();


        self::created(function ($model) {


            if (in_array($model->package->access_type, [AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_SKILL, AccessType::LEARN_PATH_CERTIFICATE]))
                event(new CheckCompletedCoursesEvent($model->user));
        });
    }

    public function package()
    {
        return $this->belongsTo(PackageSubscription::class, 'package_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('expired_at', '>', now()->format('Y-m-d'))
            ->whereIn('status', [SubscribeStatus::ACTIVE, SubscribeStatus::TRIAL]);
    }

    public function isActive()
    {
        return $this->expired_at > now()->format('Y-m-d');
    }

    public function courseEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'user_subscription_id', 'id');
    }
}
