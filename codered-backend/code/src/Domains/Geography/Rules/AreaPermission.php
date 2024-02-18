<?php

namespace App\Domains\Geography\Rules;

use App\Foundation\BasicEnum;

class AreaPermission extends BasicEnum
{
    const MODULE = 'Area';

    const AREA_INDEX = [
        "name"    => "Areas list",
        "ability" => "area.index"
    ];

    const AREA_CREATE = [
        "name"    => "Create",
        "ability" => "area.create"
    ];

    const AREA_EDIT = [
        "name"    => "Edit",
        "ability" => "area.edit",
    ];

    const AREA_DELETE = [
        "name"    => "Delete",
        "ability" => "area.delete"
    ];

}
