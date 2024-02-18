<?php

namespace App\Domains\Payments\Enum;

use App\Foundation\BasicEnum;
use App\Domains\Course\Models\Course;
use App\Domains\Payments\Models\PackageSubscription;

class PayableType extends BasicEnum
{
    const SUBSCRIPTION = PackageSubscription::class;
    const MICRODEGREE = Course::class;

}
