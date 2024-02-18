<?php


namespace App\Domains\Course\Rules;


use App\Foundation\BasicEnum;

class LessonObjectivePermission extends BasicEnum
{
    const MODULE = 'Lab Objectives';

    const LAB_OBJECTIVE_INDEX = [
        "name"    => "Lab objectives list",
        "ability" => "lab.index"
    ];

    const LAB_OBJECTIVE_CREATE = [
        "name"    => "Create",
        "ability" => "lab.create"
    ];

    const LAB_OBJECTIVE_EDIT = [
        "name"    => "Edit",
        "ability" => "lab.edit",
    ];

    const LAB_OBJECTIVE_DELETE = [
        "name"    => "Delete",
        "ability" => "lab.delete"
    ];
}
