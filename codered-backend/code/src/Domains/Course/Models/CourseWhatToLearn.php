<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;
use App\Domains\UserActivity\Traits\Loggable;
use App\Domains\Course\Models\Course;
use App\Domains\Uploads\Models\Upload;

class CourseWhatToLearn extends Model
{
    use Loggable;

    protected $fillable = ['course_id', 'title','description','image_id'];


    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function setImageIdAttribute(?string $image_id)
    {
        if (!empty($image_id)) {
            return $this->attributes['image_id'] = $image_id;
        }

        return $this->attributes['image_id'] = null;
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
