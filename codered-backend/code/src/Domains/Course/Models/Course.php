<?php

namespace App\Domains\Course\Models;

use App\Domains\Challenge\Models\Challenge;
use App\Domains\Cms\Models\Brand;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Partner\Models\Partner;
use App\Domains\User\Models\User;
use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;
use App\Domains\Uploads\Models\Upload;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\CourseLevel;
use App\Domains\Course\Models\Lookups\CourseTag;
use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Course\Models\CourseWhatToLearn;
use App\Domains\Course\Models\JobRollable;

/**
 * @property mixed course_type
 * @property mixed id
 * @property mixed course_category_id
 */
class Course extends Model
{

    use Loggable;

    protected $fillable = [
        'name', 'brief', 'intro_video', 'description', 'image_id', 'learn', 'course_category_id', 'level',
        'timing', 'activation', 'is_featured', 'course_type', 'fees', 'user_id', 'cover_id', 'is_free', 'is_essential',
        'prerequisites', 'slug_url', 'commission_percentage', 'subtitles', 'price', 'discount_price', 'is_editorial_pick', 'is_best_seller',
        'menu_cover_id', 'metadata', 'sku', 'course_sub_category_id', 'internal_name', "advances", 'challenge_id', 'cyberq_course_id',
        'agg_lessons', 'agg_avg_reviews', 'agg_count_reviews', 'agg_count_course_enrollment', 'agg_count_course_chapters', 'agg_reviews'
    ];

    protected $attributes = [
        'activation'  => CourseActivationStatus::DRAFT,
        'course_type' => CourseType::COURSE,
        'is_featured' => 0,
        'level'       => CourseLevel::BEGINNER,
    ];

    protected $casts = [
        'learn'         => 'array',
        'prerequisites' => 'array',
        'subtitles'     => 'array',
        'metadata'      => 'array',
        'agg_lessons'   => 'array',
        'agg_reviews'   => 'array',
    ];

    protected $with = [
        // 'category', 'image'
    ];

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function surveys()
    {
        return $this->hasMany(UserCourseSurvey::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function checkpoints()
    {
        return $this->hasMany(Lesson::class)->where('type', LessonType::CHECKPOINT);
    }

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function cover()
    {
        return $this->hasOne(Upload::class, 'id', 'cover_id');
    }

    public function rates()
    {
        return $this->hasMany(CourseReview::class, 'course_id');
    }

    public function menu_cover()
    {
        return $this->hasOne(Upload::class, 'id', 'menu_cover_id');
    }

    public function completedPercentageDB($user)
    {

        return $this->completedPercentageLoad()->where('user_id', $user->id)->pluck('completed_percentage')->first();
    }

    public function completedPercentageLoad()
    {

        return $this->hasMany(CompletedCoursePercentage::class);
    }

    public function finalExamTimer($user)
    {
        $finalExamTimer = $this->hasMany(FinalAssessmentTimer::class)->where('user_id', $user->id)->first();
        if ($finalExamTimer) {
            return $finalExamTimer->time;
        } else return null;
    }

    public function finalExamStartedAt($user)
    {
        $finalExamTimer = $this->hasMany(FinalAssessmentTimer::class)->where('user_id', $user->id)->first();
        if ($finalExamTimer) {
            return $finalExamTimer->started_at;
        } else return null;
    }

    public function completedCoursePercentage()
    {
        return $this->hasMany(CompletedCoursePercentage::class, 'course_id', 'id');
    }

    public function completedCourses()
    {
        return $this->hasMany(CompletedCourses::class, 'course_id', 'id');
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function completedPercentageCalcualate($user)
    {
        $total_lessons = $this->lessons()->active()
            ->where('type', '!=', LessonType::CHECKPOINT)
            ->whereHas('chapter', function ($query) {
                $query->active();
            })->count();
        $completed_lessons = $user->watched_lessons()
            ->where(['lessons.course_id' => $this->id])
            ->count();
        if ($total_lessons == 0) return 0;
        $percentage = $completed_lessons / $total_lessons * 100;

        return $percentage > 100 ? 100 : $percentage;
    }

    public function completedPercentage($user, $calculate_with_db = true)
    {
        $percentage = null;
        if ($calculate_with_db) {
            $percentage = $this->completedPercentageDB($user);
        }

        if (is_null($percentage) || empty($percentage)) {
            $percentage = $this->completedPercentageCalcualate($user);
        }
        return $percentage;
    }

    public function isCourseFinished($user)
    {
        if (!$user) return false;

        $is_finished = $this->hasMany(CompletedCoursePercentage::class)->where('user_id', $user->id)->pluck('is_finished')->first();
        if (is_null($is_finished)) {
            $is_finished = false;
        }

        return $is_finished;
    }

    public function microdegree()
    {
        return $this->hasOne(CourseMicrodgree::class);
    }

    public function packages()
    {
        return $this->hasMany(CoursePackage::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

    //TODO: rename to instructor
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id', 'id');
    }

    public function getInternalNameOrNameAttribute()
    {
        return $this->internal_name ?? $this->name;
    }

    public function sub()
    {
        return $this->belongsTo(CourseCategory::class, 'course_sub_category_id', 'id');
    }

    public function assessments()
    {
        return $this->hasMany(CourseAssessment::class, 'course_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(CourseTag::class);
    }

    public function jobRoles()
    {
        return $this->morphToMany(JobRole::class, 'job_rollable');
    }

    public function specialtyAreas()
    {
        return $this->morphToMany(SpecialtyArea::class, 'specialty_areable');
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'course_partner');
    }

    public function setImageIdAttribute(?string $image_id)
    {
        if (!empty($image_id)) {
            return $this->attributes['image_id'] = $image_id;
        }

        return $this->attributes['image_id'] = null;
    }

    public function setCoverIdAttribute(?string $cover_id)
    {
        if (!empty($cover_id)) {
            return $this->attributes['cover_id'] = $cover_id;
        }

        return $this->attributes['cover_id'] = null;
    }

    public function getLearnAttribute($learn)
    {
        if (is_string($learn) || $learn == '[]') {
            return json_decode($learn);
        }

        return $learn;
    }

    public function getPrerequisitesAttribute($prerequisites)
    {
        if (is_string($prerequisites) || $prerequisites == '[]') {
            return json_decode($prerequisites);
        }

        return $prerequisites;
    }

    public function getSubtitlesAttribute($subtitles)
    {
        if (is_string($subtitles) || $subtitles == '[]') {
            return json_decode($subtitles);
        }

        return $subtitles;
    }

    public function completed_course_percentages_by_enterprise()
    {
        return $this->belongsToMany(User::class, 'completed_course_percentages')
            ->withPivot(['completed_percentage']);
    }

    public function course_review_by_enterprise()
    {
        return $this->belongsToMany(User::class, 'course_reviews')
            ->withPivot(['rate']);
    }

    public function watch_history_time_by_enterprise()
    {
        return $this->belongsToMany(User::class, 'watch_history_times')
            ->withPivot(['watched_time']);
    }

    public function completed_course_by_enterprise()
    {
        return $this->belongsToMany(User::class, 'completed_courses')
            ->withPivot(['degree']);
    }

    public function scopeActive($query)
    {
        return $query->where('activation', CourseActivationStatus::ACTIVE);
    }

    public function scopeActiveOrHide($query)
    {
        return $query->where('activation', CourseActivationStatus::ACTIVE)->orWhere('activation', '=', CourseActivationStatus::HIDDEN);;
    }

    public function scopeDraft($query)
    {
        return $query->where('activation', CourseActivationStatus::DRAFT);
    }

    public function scopeCourse($query)
    {
        return $query->where('course_type', CourseType::COURSE);
    }

    public function scopeMicrodegrees($query)
    {
        return $query->where('course_type', CourseType::MICRODEGREE);
    }

    public function scopeCourseCertification($query)
    {
        return $query->where('course_type', CourseType::COURSE_CERTIFICATION);
    }

    // public function reviews()
    // {
    //     return $this->belongsToMany(User::class, 'course_reviews')->withPivot('rate', 'recommendation');
    // }

    public function cousre_enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    public function course_enrollments()
    {
        return $this->cousre_enrollments();
    }

    public function cousre_enrollments_by_enterprise()
    {
        return $this->belongsToMany(User::class, 'course_enrollment');
    }

    public function reviews()
    {
        return $this->hasMany(CourseReview::class, 'course_id');
    }

    public function course_learn()
    {

        return $this->belongsTo(CourseWhatToLearn::class, 'id', 'course_id');
    }

    public function course_learns()
    {

        return $this->hasMany(CourseWhatToLearn::class);
    }

    public function tools()
    {
        return $this->belongsToMany(Brand::class, 'tools_course', 'course_id', 'tool_id');
    }

    public function watch_history_times()
    {
        return $this->hasMany(WatchHistoryTime::class, 'course_id');
    }
}
