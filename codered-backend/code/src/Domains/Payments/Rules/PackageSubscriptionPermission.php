<?php

namespace App\Domains\Payments\Rules;

use App\Foundation\BasicEnum;

class PackageSubscriptionPermission extends BasicEnum
{
    const MODULE = 'Packages And Plans';

    const PACKAGE_SUBSCRIPTION_INDEX = [
        "name"    => "Show Plans",
        "ability" => "package.index"
    ];

    const PACKAGE_SUBSCRIPTION_CREATE = [
        "name"    => "Create Plan",
        "ability" => "package.create"
    ];

    const PACKAGE_SUBSCRIPTION_EDIT = [
        "name"    => "Edit",
        "ability" => "package.edit",
    ];

    const PACKAGE_SUBSCRIPTION_DELETE = [
        "name"    => "Delete",
        "ability" => "package.delete"
    ];

}