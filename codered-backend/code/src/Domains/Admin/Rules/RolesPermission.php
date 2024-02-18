<?php


namespace App\Domains\Admin\Rules;

use App\Foundation\BasicEnum;

class RolesPermission extends BasicEnum
{
    const MODULE = 'Roles';

    const ROLE_INDEX = [
        "name" => "Roles list",
        "ability" => "role.index"
    ];

    const ROLE_CREATE = [
        "name" => "Create",
        "ability" => "role.create"
    ];

    const ROLE_EDIT = [
        "name" => "Edit",
        "ability" => "role.edit",
    ];

    const ROLE_DELETE = [
        "name" => "Delete",
        "ability" => "role.delete"
    ];

}
