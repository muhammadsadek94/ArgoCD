<?php

namespace App\Domains\Course\Models;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;


class CourseReview  extends Model
{

    protected $fillable = ['user_id', 'course_id', 'name', 'rate' ,'user_goals','recommendation', 'activation'];


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

}
