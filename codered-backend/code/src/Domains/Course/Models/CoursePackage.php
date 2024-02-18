<?php

namespace App\Domains\Course\Models;

use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;

class CoursePackage extends Model
{
    use Loggable;

    protected $fillable = ['name','amount','type','features','url','course_id'];

    protected $casts = [
        'features' => 'array'
    ];


}
