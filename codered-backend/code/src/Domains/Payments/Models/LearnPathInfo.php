<?php

namespace App\Domains\Payments\Models;

use App\Domains\Cms\Models\Brand;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\JobRole;
use App\Domains\Course\Models\LearnPathCertificate;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Course\Models\SpecialtyArea;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\UserLearnPathCertificateResource;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Models\UserSubscription;
use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;

class LearnPathInfo extends Model
{

    use Loggable;
    protected $fillable = [
        'name', 'slug_url', 'overview', 'description', 'url', 'activation', 'type', 'features',
        'price', 'price_description', 'payment_url', 'for_who', 'learn', 'skills', 'skills_description', 'jobs', 'jobs_description', 'prerequisite', 'faq',
        'image_id',  'cover_id', 'category_id', 'package_id', 'subtitles', 'avg_salary', 'sub_category_id','sku' , 'level','metadata','agg_courses'
    ];

    protected $casts = [
        'features' => 'array',
        'learn' => 'array',
        'description' => 'array',
        'jobs' => 'array',
        'skills' => 'array',
        'prerequisite' => 'array',
        'faq' => 'array',
        'metadata' => 'array',
        'subtitles' => 'array',
        'brands' => 'array',
        'agg_courses' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id', 'id');
    }


    public function cover()
    {
        return $this->hasOne(Upload::class, 'id', 'cover_id');
    }

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }




    public function jobRoles()
    {
        return $this->morphToMany(JobRole::class, 'job_rollable');
    }

    public function specialtyAreas()
    {
        return $this->morphToMany(SpecialtyArea::class, 'specialty_areable');
    }
    public function scopeActive($query)
    {
        return $query->where('learn_path_infos.activation', 1);
    }

    public function packages()
    {
        return $this->hasOne(PackageSubscription::class, 'id', 'package_id');
//        return $this->belongsToMany(Course::class, 'course_weights')->withPivot('weight')->withPivot('sort');
    }



    // enterprise
    public function Package()
    {
        return $this->hasOne(PackageSubscription::class, 'id', 'package_id');
//        return $this->belongsToMany(Course::class, 'course_weights')->withPivot('weight')->withPivot('sort');
    }

    // public function scopeCourses($query)
    // {

    //     if($this->Package)
    //     return $query->Package->courses()->active();
    // }

    // public function courses()
    // {

    //     if($this->Package)
    //         return $this->Package->courses()->active();
    // }

    public function courses()
    {
        return $this->hasMany(LearnPathCourse::class, 'learn_path_id');
    }


    public function pathPackages()
    {
        return $this->hasMany(LearnPathPackage::class, 'path_id');
    }



    public function categories()
    {
        return $this->hasMany(LearnPathCategory::class, 'learn_path_id');
    }

    public function scopeSkill($query) {
        return $query->where('type', LearnPathType::SKILL);
    }

    public function scopeCareer($query) {
        return $query->where('type', LearnPathType::CAREER);
    }

    public function scopeCertificate($query) {
        return $query->where('type', LearnPathType::CERTIFICATE);
    }

    public function tools(){
        return $this->belongsToMany(Brand::class, 'brand_learn_path', 'learn_path_id');
    }

    public function scopePaths($query) {
        return $query->whereIn('type', [LearnPathType::SKILL, LearnPathType::CAREER, LearnPathType::CERTIFICATE]);
    }

    public function scopeSkillPath($query) {
        return $query->whereIn('type', [LearnPathType::SKILL]);
    }


    public function scopeCareerPath($query) {
        return $query->whereIn('type', [LearnPathType::CAREER]);
    }

    public function scopeBundles($query) {
        return $query->whereIn('type', [LearnPathType::BUNDLE_COURSES, LearnPathType::BUNDLE_CATEGORY]);
    }

    public function package_subscription() {
        return $this->hasMany(PackageSubscription::class, 'learn_path_id');
    }


    public function getPathCertificate($user_id) {
        return $this->hasMany(LearnPathCertificate::class, 'learnpath_id')->where('user_id', $user_id);
    }

    public function allPathCertificates() {
        return $this->hasMany(LearnPathCertificate::class, 'learnpath_id');
    }

    public function allCourses(){
        return $this->hasManyThrough(Course::class, LearnPathCourse::class, 'learn_path_id', 'id', 'id', 'course_id');
    }

    public function completedCourses()
    {
        return $this->hasManyThrough(CompletedCourses::class, LearnPathCourse::class , 'learn_path_id', 'course_id', 'id', 'course_id');
    }

    public function user_subscriptions() {
        return $this->hasManyThrough(UserSubscription::class, PackageSubscription::class, 'learn_path_id', 'package_id', 'id', 'id');
    }

    public function courses_load()
    {
        return $this->hasManyThrough(Course::class, LearnPathCourse::class, 'learn_path_id', 'id', 'id', 'course_id');
    }

    public function labs() {
        return $this->hasManyThrough(Course::class, LearnPathCourse::class, 'learn_path_id', 'id', 'id', 'course_id')
                        ->join('chapters', 'courses.id', '=', 'chapters.course_id')
                        ->join('lessons', 'chapters.id', '=', 'lessons.chapter_id')
                        ->where('lessons.type', LessonType::LAB)
                        ->select('lessons.*');
    }

    public function videos() {
        return $this->hasManyThrough(Course::class, LearnPathCourse::class, 'learn_path_id', 'id', 'id', 'course_id')
                        ->join('chapters', 'courses.id', '=', 'chapters.course_id')
                        ->join('lessons', 'chapters.id', '=', 'lessons.chapter_id')
                        ->where('lessons.type', LessonType::VIDEO)
                        ->select('lessons.*');
    }

}



