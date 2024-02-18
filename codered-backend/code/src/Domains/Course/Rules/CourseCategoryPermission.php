<?php

namespace App\Domains\Course\Rules;

use App\Foundation\BasicEnum;

class CourseCategoryPermission extends BasicEnum
{
    const MODULE = 'Course Categories';

    const COURSE_CATEGORY_INDEX = [
        "name"    => "Courses categories list",
        "ability" => "course_category.index"
    ];

    const COURSE_CATEGORY_CREATE = [
        "name"    => "Create",
        "ability" => "course_category.create"
    ];

    const COURSE_CATEGORY_EDIT = [
        "name"    => "Edit",
        "ability" => "course_category.edit",
    ];

    const COURSE_CATEGORY_DELETE = [
        "name"    => "Delete",
        "ability" => "course_category.delete"
    ];

}
