<?php

namespace App\Domains\Payments\Models;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\Course;
use App\Domains\Enterprise\Enum\LicneseStatusType;
use App\Domains\Enterprise\Models\License;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;
use App\Domains\Payments\Models\PaymentIntegration;

/**
 * @property mixed id
 * @property mixed access_type
 * @property mixed type
 * @property mixed duration
 * @property Course[] $courses
 */
class PackageSubscription extends Model
{

    use Loggable;

    protected $fillable = [
        'name', 'amount', 'description', 'url', 'activation',
        'access_type', 'access_id', 'access_permission',
        'type', 'duration', 'deadline_type', 'enterprise_id',
        'expiration_date', 'expiration_days', 'learn_path_id',
        'sku', 'free_trial_days', 'is_installment', 'installment_count', 'course_type'
    ];

    protected $casts = [
        'description' => 'array',
        'access_id'   => 'array'
    ];

    public function integrations()
    {
        return $this->morphMany(PaymentIntegration::class, 'payable');
    }

    public function setTypeAttribute($type)
    {
        if ($type > SubscriptionPackageType::CUSTOM)
            return $this->attributes['type'] = $type;
        if ($this->access_type == AccessType::COURSES ||
            $this->access_type == AccessType::INDIVIDUAL_COURSE) {
            return $this->attributes['type'] = SubscriptionPackageType::CUSTOM;
        }
        return $this->attributes['type'] = $type;
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }

    public function scopePro($query)
    {
        return $query->where('access_type', AccessType::PRO);
    }

    public function scopeCategoriesBundle($query)
    {
        return $query->where('access_type', AccessType::COURSE_CATEGORY);
    }

    public function scopeCoursesBundle($query)
    {
        return $query->where('access_type', AccessType::COURSES);
    }

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'package_subscription_chapter', 'package_subscription_id', 'chapter_id')
            ->withPivot('is_free_trial', 'after_installment_number');
    }

    public function package_chapters()
    {
        return $this->hasMany(PackageChapter::class, 'package_subscription_id');
    }

    // enterprise
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_weights')->withPivot('weight')->withPivot('sort');
    }

    public function labs()
    {
        return $this->belongsToMany(Course::class, 'course_weights')
            ->join('chapters', 'courses.id', '=', 'chapters.course_id')
            ->join('lessons', 'chapters.id', '=', 'lessons.chapter_id')
            ->where('lessons.type', LessonType::LAB)
            ->select('lessons.*');
    }

    public function videos() {
        return $this->belongsToMany(Course::class, 'course_weights')
            ->join('chapters', 'courses.id', '=', 'chapters.course_id')
            ->join('lessons', 'chapters.id', '=', 'lessons.chapter_id')
            ->where('lessons.type', LessonType::VIDEO)
            ->select('lessons.*');
    }

    public function enterprise_users()
    {
        return $this->hasMany(License::class, 'package_id')->where('activation', 1)->where('status', LicneseStatusType::USED);
    }

}
