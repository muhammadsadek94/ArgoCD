<?php

namespace App\Domains\Course\Rules;

use App\Foundation\BasicEnum;

class CoursePermission extends BasicEnum
{
    const MODULE = 'Course Management';

    const COURSE_INDEX = [
        "name"    => "Courses list",
        "ability" => "course.index"
    ];

    const COURSE_CREATE = [
        "name"    => "Create",
        "ability" => "course.create"
    ];

    const COURSE_EDIT = [
        "name"    => "Edit",
        "ability" => "course.edit",
    ];

    const COURSE_DELETE = [
        "name"    => "Delete",
        "ability" => "course.delete"
    ];

}
