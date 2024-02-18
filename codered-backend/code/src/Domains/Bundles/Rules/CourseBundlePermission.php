<?php

namespace App\Domains\Bundles\Rules;

use App\Foundation\BasicEnum;

class CourseBundlePermission extends BasicEnum
{
    const MODULE = 'Course Bundles';

    const COURSE_BUNDLE_INDEX = [
        "name"    => "Course Bundle list",
        "ability" => "course_bundle.index"
    ];

    const COURSE_BUNDLE_CREATE = [
        "name"    => "Create",
        "ability" => "course_bundle.create"
    ];

    const COURSE_BUNDLE_EDIT = [
        "name"    => "Edit",
        "ability" => "course_bundle.edit",
    ];

    const COURSE_BUNDLE_DELETE = [
        "name"    => "Delete",
        "ability" => "course_bundle.delete"
    ];

}
