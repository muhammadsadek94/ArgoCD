<?php

namespace App\Domains\Course\Models;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class CompletedCoursePercentage extends Model
{

    protected $fillable = [
        'user_id', 'course_id', 'completed_percentage', 'is_finished', 'created_at'
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
