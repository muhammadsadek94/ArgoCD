<?php

namespace App\Domains\Course\Rules;

use App\Foundation\BasicEnum;

class SpecialtyAreaPermission extends BasicEnum
{
    const MODULE = 'Specialty Area';

    const SPECIALTY_AREA_INDEX = [
        "name"    => "Specialty Area list",
        "ability" => "specialty_area.index"
    ];

    const SPECIALTY_AREA_CREATE = [
        "name"    => "Create",
        "ability" => "specialty_area.create"
    ];

    const SPECIALTY_AREA_EDIT = [
        "name"    => "Edit",
        "ability" => "specialty_area.edit",
    ];

    const SPECIALTY_AREA_DELETE = [
        "name"    => "Delete",
        "ability" => "specialty_area.delete"
    ];

}
