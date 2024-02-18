<?php


namespace App\Domains\Course\Rules;


use App\Foundation\BasicEnum;

class LessonTaskPermission extends BasicEnum
{
    const MODULE = 'Lab Task';

    const LAB_TASK_INDEX = [
        "name"    => "Lab tasks list",
        "ability" => "lab.index"
    ];

    const LAB_TASK_CREATE = [
        "name"    => "Create",
        "ability" => "lab.create"
    ];

    const LAB_TASK_EDIT = [
        "name"    => "Edit",
        "ability" => "lab.edit",
    ];

    const LAB_TASK_DELETE = [
        "name"    => "Delete",
        "ability" => "lab.delete"
    ];
}
