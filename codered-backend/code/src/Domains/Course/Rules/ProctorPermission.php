<?php

namespace App\Domains\Course\Rules;

use App\Foundation\BasicEnum;

class ProctorPermission extends BasicEnum
{
    const MODULE = 'Proctors Management';

    const PROCTOR_INDEX = [
        "name"    => "Proctors list",
        "ability" => "proctors.index"
    ];

    const PROCTOR_CREATE = [
        "name"    => "Create",
        "ability" => "proctors.create"
    ];

    const PROCTOR_EDIT = [
        "name"    => "Edit",
        "ability" => "proctors.edit",
    ];

    const PROCTOR_DELETE = [
        "name"    => "Delete",
        "ability" => "proctors.delete"
    ];

}
