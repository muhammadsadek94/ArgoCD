<?php

namespace App\Domains\User\Rules;

use App\Foundation\BasicEnum;

class InstructorPermission extends BasicEnum
{
    const MODULE = 'Instructors';

    const INSTRUCTOR_INDEX = [
        "name"    => "Show Instructors",
        "ability" => "instructor.index"
    ];

    const INSTRUCTOR_CREATE = [
        "name"    => "Create",
        "ability" => "instructor.create"
    ];

    const INSTRUCTOR_EDIT = [
        "name"    => "Edit",
        "ability" => "instructor.edit",
    ];

    const INSTRUCTOR_DELETE = [
        "name"    => "Delete",
        "ability" => "instructor.delete"
    ];

    const LOGGED_AS_INSTRUCTOR = [
        "name"    => "Logged as specific instructor in frontend",
        "ability" => "instructor.logged_in"
    ];

}
