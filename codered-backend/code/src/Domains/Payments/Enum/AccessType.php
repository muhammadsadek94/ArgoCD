<?php

namespace App\Domains\Payments\Enum;

use App\Foundation\BasicEnum;

class AccessType extends BasicEnum
{
    const PRO = 1;
    const COURSE_CATEGORY = 2;
    const COURSES = 3;
    const INDIVIDUAL_COURSE = 4;
    const LEARN_PATH_SKILL = 5;
    const LEARN_PATH_CAREER = 6;
    const LEARN_PATH_CERTIFICATE = 7;

}
