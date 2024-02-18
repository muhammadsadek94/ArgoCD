<?php

namespace App\Domains\Course\Rules;

use App\Foundation\BasicEnum;

class CourseTagPermission extends BasicEnum
{
    const MODULE = 'Course Tags';

    const COURSE_TAG_INDEX = [
        "name"    => "Courses tags list",
        "ability" => "course_tag.index"
    ];

    const COURSE_TAG_CREATE = [
        "name"    => "Create",
        "ability" => "course_tag.create"
    ];

    const COURSE_TAG_EDIT = [
        "name"    => "Edit",
        "ability" => "course_tag.edit",
    ];

    const COURSE_TAG_DELETE = [
        "name"    => "Delete",
        "ability" => "course_tag.delete"
    ];

}
