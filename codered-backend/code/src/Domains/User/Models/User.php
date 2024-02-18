<?php

namespace App\Domains\User\Models;

use App\Domains\Blog\Models\Article;
use App\Domains\Challenge\Models\UserCompetition;
use App\Domains\Challenge\Models\UserFlag;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Payments\Models\PaymentTransactions;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\FinalAssessmentTimer;
use App\Domains\Course\Models\LearnPathCertificate;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\LessonTask;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Course\Models\Lookups\CourseTag;
use App\Domains\Course\Models\WatchingHistory;
use App\Domains\Enterprise\Models\EnterpriseInfo;
use App\Domains\Enterprise\Models\License;
use App\Domains\Geography\Models\City;
use App\Domains\Geography\Models\Country;
use App\Domains\Notification\Models\Notification;
use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Events\User\UserCreated;
use App\Domains\User\Events\User\UserDeleted;
use App\Domains\User\Events\User\UserUpdated;
use App\Domains\User\Models\Lookups\Goal;
use App\Domains\User\Models\Lookups\UserTag;
use App\Domains\UserActivity\Traits\Loggable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use INTCore\OneARTFoundation\Model;
use Laravel\Passport\HasApiTokens;
use App\Domains\Comment\Models\Comment;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Payments\Models\LearnPathInfo;
use Carbon\Carbon;
use Framework\Traits\SelectColumnTrait;

/**
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed phone
 * @property mixed email
 * @property mixed password
 * @property mixed image_id
 * @property mixed social_id
 * @property mixed social_type
 * @property mixed type
 * @property mixed password_reset_code
 * @property mixed temp_email_code
 * @property mixed temp_phone_code
 * @property mixed cover_id
 * @property mixed activation
 * @property mixed level_experience
 * @property mixed categories
 * @property mixed tags
 * @property mixed id
 * @property mixed daily_target
 * @property mixed transactions
 * @property mixed completed_courses
 * @property mixed full_name
 * @property mixed active_campaign_id
 * @property mixed source
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use HasApiTokens, Notifiable, Loggable, SelectColumnTrait;
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    protected $fillable = [
        "first_name", "last_name", "company_name", "phone", "email", 'password', 'activation', 'image_id', 'social_id',
        'social_type', 'type',
        'password_reset_code', 'temp_email_code', 'temp_phone_code', 'cover_id', 'week_start_date', 'week_end_date',
        'country_id', 'city_id', 'gender', 'birth_date', 'level_experience', 'daily_target', 'weekly_target',
        'active_campaign_id', 'oauth2_client_id', 'source', 'enterprise_id', 'subaccount_id', 'selected_days',
        'display_name', 'is_display_name_updated', 'is_profile_picture_updated',
        'vital_source_ref', 'utm_data','viral_source_password'
    ];

    protected $hidden = [
        'password', 'image_id', 'social_id', 'social_type', 'temp_email_code', 'temp_phone_code'
    ];

    protected $attributes = [
        'source' => 'codered'
    ];

    protected $appends = [
        'full_name'
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class,
        'updated' => UserUpdated::class,
        'deleted' => UserDeleted::class,
    ];

    protected $guarded = ['access_token'];

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function cover()
    {
        return $this->hasOne(Upload::class, 'id', 'cover_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, "notifiable_id", "id")->orderBy("created_at", "desc");
    }

    public function licenses()
    {
        return $this->hasMany(License::class, 'enterprise_id', 'id');
    }
    public function subLicenses()
    {
        return $this->hasMany(License::class, 'subaccount_id', 'id');
    }

    public function userLicenses()
    {
        return $this->hasMany(License::class, 'user_id', 'id');
    }
    public function subAccountLicenses()
    {
        return $this->hasMany(License::class, 'subaccount_id', 'id');
    }


    public function learn_paths()
    {
        return $this->belongsToMany(PackageSubscription::class, 'enterprise_learn_paths', 'enterprise_id', 'package_id');
    }

    public function enterpriseSubAccount()
    {
        return $this->belongsTo(User::class, 'subaccount_id', 'id');
    }

    public function enterpriseAccount()
    {
        return $this->belongsTo(User::class, 'enterprise_id', 'id');
    }

    public function enterpriseInfo()
    {
        return $this->belongsTo(EnterpriseInfo::class, 'id', 'enterprise_id');
    }

    /**
     * tags that's assigned to user by administration or system
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usertags()
    {
        return $this->belongsToMany(UserTag::class);
    }

    /**
     * prefered courses tags selected by user in onboarding
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(CourseTag::class);
    }

    public function categories()
    {
        return $this->belongsToMany(CourseCategory::class);
    }

    public function goals()
    {
        return $this->belongsToMany(Goal::class);
    }

    /**
     * View completed lessons
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function watched_lessons()
    {
        return $this->belongsToMany(Lesson::class, 'watched_lessons', 'user_id', 'lesson_id')
            ->withPivot(['created_at'])->withTimestamps();
    }

    public function watched_lessons_time()
    {
        return $this->belongsToMany(User::class, 'watch_history_times', 'user_id', 'id')->sum('watched_time');
    }

    public function all_course_enrollments()
    {
        return $this->belongsToMany(Course::class, 'course_enrollment', 'user_id', 'course_id')
            ->withPivot('expired_at', 'user_subscription_id')
            ->withTimestamps();
    }

    public function course_enrollments()
    {
        return $this->belongsToMany(Course::class, 'course_enrollment', 'user_id', 'course_id')
            ->where('course_type', CourseType::COURSE)->withTimestamps();
    }

    public function article_likes()
    {
        return $this->belongsToMany(Article::class, 'article_likes', 'user_id', 'article_id')
            ->withTimestamps();
    }

    public function microdegree_enrollments()
    {
        return $this->belongsToMany(Course::class, 'course_enrollment', 'user_id', 'course_id')
            ->where('course_type', CourseType::MICRODEGREE)
            ->withPivot(['created_at', 'expired_at'])
            ->withTimestamps();
    }

    public function microdegree_certifications_enrollments()
    {
        return $this->belongsToMany(Course::class, 'course_enrollment', 'user_id', 'course_id')
            ->where(function ($query) {
                $query->where('course_type', CourseType::MICRODEGREE)
                    ->orWhere('course_type', CourseType::COURSE_CERTIFICATION);
            })
            ->withPivot(['created_at', 'expired_at', 'id'])
            ->withTimestamps();
    }

    public function certifications_enrollments()
    {
        return $this->belongsToMany(Course::class, 'course_enrollment', 'user_id', 'course_id')
            ->where('course_type', CourseType::COURSE_CERTIFICATION)
            ->withPivot(['created_at', 'expired_at'])
            ->withTimestamps();
    }

    public function active_certifications()
    {
        return $this->hasManyThrough(Course::class, CourseEnrollment::class, 'user_id', 'id', 'id', 'course_id')
            ->where('course_type', CourseType::COURSE_CERTIFICATION)
            ->where(function ($query) {
                $query->where('expired_at', '>', Carbon::now())
                    ->orWhereNull('expired_at');
            });
    }



    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user');
    }

    /**
     * Save user history
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function course_history()
    {
        return $this->belongsToMany(Course::class, 'watching_histories', 'user_id', 'course_id')
            ->withTimestamps()
            ->withPivot(['course_id', 'created_at']);
    }

    public function completed_course_percentages()
    {
        return $this->belongsToMany(Course::class, 'completed_course_percentages', 'user_id', 'course_id')
            ->withTimestamps()
            ->withPivot(['course_id', 'created_at']);
    }

    /**
     * Save user history
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lesson_history()
    {
        return $this->belongsToMany(Lesson::class, 'watching_histories', 'user_id', 'lesson_id')
            ->withTimestamps()
            ->withPivot(['course_id', 'created_at']);
    }

    public function instructor_profile()
    {
        return $this->hasOne(InstructorProfile::class);
    }

    public function instructor_courses()
    {
        return $this->hasMany(Course::class);
    }

    public function accessTokens()
    {
        return $this->hasMany(OauthAccessToken::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransactions::class);
    }

    public function completed_courses()
    {
        return $this->hasMany(CompletedCourses::class);
    }

    public function completed_learn_paths()
    {
        return $this->hasMany(LearnPathCertificate::class);
    }

    public function completed_courses_percentage()
    {
        return $this->hasMany(CompletedCoursePercentage::class);
    }

    public function active_subscription()
    {
        return $this->hasMany(UserSubscription::class)->active();
    }

    public function active_packages()
    {
        return $this->hasManyThrough(PackageSubscription::class, UserSubscription::class, 'user_id', 'id', 'id', 'package_id')
            ->where('user_subscriptions.expired_at', '>', Carbon::now());
    }

    public function purchased_subscription()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function enterprise_active_subscription()
    {
        return $this->active_subscription()->where('subscription_id', "LIKE", "%EP-%");
    }

    public function lesson_tasks()
    {
        return $this->belongsToMany(LessonTask::class, 'lesson_task_user', 'user_id', 'lesson_task_id');
    }

    public function competitions()
    {
        return $this->hasMany(UserCompetition::class);
    }

    public function flags()
    {
        return $this->hasMany(UserFlag::class);
    }

    public function hasActiveSubscription($type = AccessType::PRO)
    {
        return $this->active_subscription()
            ->select(SelectColumnTrait::$userActiveSubscriptionsColumns)
            ->whereHas('package', function ($query) use ($type) {
                return $query->where('access_type', $type);
            })->first();
    }

    public function getActiveSubscription($type = [AccessType::PRO])
    {
        return $this->active_subscription()
            ->whereHas('package', function ($query) use ($type) {
                return $query->whereIn('access_type', $type);
            })->get();
    }

    public function getActiveSubscriptionWithTypeList(array $types)
    {
        return $this->active_subscription()
            ->whereHas('package', function ($query) use ($types) {
                return $query->whereIn('access_type', $types);
            })->get();
    }



    public function hasAnyActiveSubscription()
    {
        return $this->active_subscription()
            ->first();
    }

    public function allowedToAccessCourse($course_id)
    {
        $active_subscriptions = $this->getActiveSubscription([AccessType::COURSES, AccessType::INDIVIDUAL_COURSE, AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_CERTIFICATE, AccessType::LEARN_PATH_SKILL]);
        $access_ids = [];
        foreach ($active_subscriptions as $subscription) {
            if (gettype($subscription->package->access_id) == 'array')
                $new_access_ids = $subscription->package->access_id;
            else
                $new_access_ids = json_decode($subscription->package->access_id, true);
            array_push($access_ids, $new_access_ids);
        }

        $access_ids = array_flatten($access_ids);
        return in_array($course_id, $access_ids);
    }

    public function allowedToAccessCategory($category_id)
    {
        $active_subscriptions = @$this->getActiveSubscription([AccessType::COURSE_CATEGORY]);
        $access_ids = [];
        foreach ($active_subscriptions as $subscription) {
            $new_access_ids = json_decode($subscription->package->access_id, true);
            array_push($access_ids, $new_access_ids);
        }

        $access_ids = array_flatten($access_ids);


        return in_array($category_id, $access_ids);
    }

    public function allowedToAccessCourseRelatedWithLeanPath(string $courseId)
    {
        $active_subscriptions = @$this->getActiveSubscriptionWithTypeList(
            [AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_CERTIFICATE, AccessType::LEARN_PATH_SKILL]
        );

        $access_ids = [];
        foreach ($active_subscriptions as $subscription) {
            $new_access_ids = json_decode($subscription->package->access_id, true);

            array_push($access_ids, $new_access_ids);
        }

        $access_ids = array_flatten($access_ids);

        return in_array($courseId, $access_ids);
    }

    public function favourite_lessons()
    {
        return $this->morphedByMany(Lesson::class, 'favourable');
    }

    public function setPasswordAttribute(?string $password)
    {
        if (!empty($password))
            $this->attributes['password'] = bcrypt($password);
    }



    public function setCountryIdAttribute(?string $country_id)
    {
        $this->attributes['country_id'] = empty($country_id) ? null : $country_id;
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

    public function setPhoneAttribute(?string $phone)
    {
        if (!empty($phone)) {
            $phone = trim($phone);
            $phone = str_replace('+', '', $phone);
            $phone = str_replace(' ', '', $phone);
            $this->attributes['phone'] = $phone;
        }
    }

    public function getActivationStatusAttribute()
    {
        /*
         * if activation status is bigger than 1000 means that the user has an activation code and
         * need to submit it to activate his account, else the account is active, suspended, need to
         * complete profile , waiting approval or etc...
         */
        return (int)($this->activation > 1000 ? UserActivation::PENDING : $this->activation);
    }

    public function getHasPasswordAttribute()
    {
        return !empty($this->password);
    }

    //full_name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }

    public function scopeUser($query)
    {
        return $query->where('type', UserType::USER);
    }

    public function scopeProvider($query)
    {
        return $query->where('type', UserType::PROVIDER);
    }

    public function finalAssignments()
    {
        return $this->hasMany(FinalAssessmentTimer::class, 'user_id');
    }

    public function access_type($course_id)
    {
    }


    public function comments()
    {
        return $this->morphToMany(Comment::class, 'owner', 'comments', 'owner_id', 'id');
    }
}
