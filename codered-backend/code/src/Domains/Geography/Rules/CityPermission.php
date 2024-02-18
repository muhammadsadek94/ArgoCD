<?php

namespace App\Domains\Geography\Rules;

use App\Foundation\BasicEnum;

class CityPermission extends BasicEnum
{
    const MODULE = 'City';

    const CITY_INDEX = [
        "name"    => "Cities list",
        "ability" => "City.index"
    ];

    const CITY_CREATE = [
        "name"    => "Create",
        "ability" => "City.create"
    ];

    const CITY_EDIT = [
        "name"    => "Edit",
        "ability" => "City.edit",
    ];

    const CITY_DELETE = [
        "name"    => "Delete",
        "ability" => "City.delete"
    ];

}
