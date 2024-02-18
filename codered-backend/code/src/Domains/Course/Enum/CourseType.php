<?php


namespace App\Domains\Course\Enum;


use App\Foundation\BasicEnum;

class CourseType extends BasicEnum
{
    const NONE = 0;
    const COURSE = 1;
    const MICRODEGREE = 2;
    const COURSE_CERTIFICATION = 3;

    public static function getList()
    {
        return [
            self::COURSE      => 'Course',
            self::MICRODEGREE => 'Micro Degree',
            self::COURSE_CERTIFICATION => 'Certification',
        ];
    }

    public static function getCourseType($type)
    {
        $list = self::getList();
        return $list[$type];
    }
}
