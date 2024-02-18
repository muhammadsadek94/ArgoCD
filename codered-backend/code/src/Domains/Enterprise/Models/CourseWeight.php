<?php

namespace App\Domains\Enterprise\Models;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Course\Models\Course;
use INTCore\OneARTFoundation\Model;

class   CourseWeight extends Model
{
    protected $fillable = ['course_id', 'package_subscription_id','weight','sort'];


    public function package()
    {
        return $this->belongsTo(PackageSubscription::class, 'packageSubscription_id', 'id');

    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

}
