<?php

namespace App\Domains\Geography\Rules;

use App\Foundation\BasicEnum;

class CountryPermission extends BasicEnum
{
    const MODULE = 'Country';

    const COUNTRY_INDEX = [
        "name"    => "Countries list",
        "ability" => "country.index"
    ];

    const COUNTRY_CREATE = [
        "name"    => "Create",
        "ability" => "country.create"
    ];

    const COUNTRY_EDIT = [
        "name"    => "Edit",
        "ability" => "country.edit",
    ];

    const COUNTRY_DELETE = [
        "name"    => "Delete",
        "ability" => "country.delete"
    ];

}
