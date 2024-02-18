<?php


namespace App\Domains\Course\Enum;


use App\Foundation\BasicEnum;

class LessonActivationStatus extends BasicEnum
{
    const ACTIVE = 1;
    const PENDING = 2;
    const DEACTIVATED = 0;

}