<?php

namespace App\Domains\Workshop\Rules;

use App\Foundation\BasicEnum;

class WorkshopPermission extends BasicEnum
{
    const MODULE = 'workshop';

    const WORKSHOP_INDEX = [
        "name"    => "workshop list",
        "ability" => "workshop.index"
    ];

    const WORKSHOP_CREATE = [
        "name"    => "Create",
        "ability" => "workshop.create"
    ];

    const WORKSHOP_EDIT = [
        "name"    => "Edit",
        "ability" => "workshop.edit",
    ];

    const WORKSHOP_DELETE = [
        "name"    => "Delete",
        "ability" => "workshop.delete"
    ];

}
