<?php

namespace App\Domains\Course\Models;

use App\Domains\Course\Events\Learnpath\CheckCompletedCoursesEvent;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;
use App\Domains\Uploads\Models\Upload;

class CompletedCourses extends Model
{
    protected $fillable = ['user_id', 'course_id', 'certificate_id', 'degree', 'certificate_number', 'created_at'];

    protected $with = [
        'course:id,name,course_sub_category_id,course_category_id,image_id,is_free',
        'certificate:id,path,full_url,mime_type'
    ];

    public static function boot()
    {
        parent::boot();


        self::created(function ($model) {
            event(new CheckCompletedCoursesEvent($model->user));
        });
    }


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function certificate()
    {
        return $this->belongsTo(Upload::class);
    }
}
