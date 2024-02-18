<?php

namespace App\Domains\Enterprise\Rules;

use App\Foundation\BasicEnum;

class EnterprisePermission extends BasicEnum
{
    const MODULE = 'Enterprise v2';

    const ENTERPRISE_INDEX = [
        "name"    => "enterprise list",
        "ability" => "enterprise.index"
    ];

    const ENTERPRISE_CREATE = [
        "name"    => "Create",
        "ability" => "enterprise.create"
    ];

    const ENTERPRISE_EDIT = [
        "name"    => "Edit",
        "ability" => "enterprise.edit",
    ];

    const ENTERPRISE_SHOW = [
        "name"    => "Show",
        "ability" => "enterprise.show",
    ];

    const ENTERPRISE_DELETE = [
        "name"    => "Delete",
        "ability" => "enterprise.delete"
    ];

    const LOGGED_AS_ENTERPRISE = [
        "name"    => "Login as Enterprise",
        "ability" => "enterprise.login_as"
    ];



}
