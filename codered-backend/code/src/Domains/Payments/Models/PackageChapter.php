<?php

namespace App\Domains\Payments\Models;

use App\Domains\Course\Models\Course;
use App\Domains\UserActivity\Traits\Loggable;
use Eloquent;

/**
 * @property mixed id
 * @property mixed access_type
 * @property mixed type
 * @property mixed duration
 * @property Course[] $courses
 */
class PackageChapter extends Eloquent
{

    use Loggable;

    protected $table = 'package_subscription_chapter';

    protected $fillable = [
        'package_subscription_id', 'chapter_id', 'course_id', 'after_installment_number', 'is_free_trial'
    ];
 

}
