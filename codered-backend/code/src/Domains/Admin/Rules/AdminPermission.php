<?php

namespace App\Domains\Admin\Rules;

use App\Foundation\BasicEnum;

class AdminPermission extends BasicEnum
{
    const MODULE = 'Admin';

    const ADMIN_INDEX = [
        "name"    => "Admins list",
        "ability" => "admin.index"
    ];

    const ADMIN_CREATE = [
        "name"    => "Create",
        "ability" => "admin.create"
    ];

    const ADMIN_EDIT = [
        "name"    => "Edit",
        "ability" => "admin.edit",
    ];

    const ADMIN_DELETE = [
        "name"    => "Delete",
        "ability" => "admin.delete"
    ];

}
