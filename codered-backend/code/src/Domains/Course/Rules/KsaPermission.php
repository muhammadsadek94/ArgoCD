<?php

namespace App\Domains\Course\Rules;

use App\Foundation\BasicEnum;

class KsaPermission extends BasicEnum
{
    public const MODULE = 'KSA';

    public const KSA_INDEX = [
        "name"    => "KSAs list",
        "ability" => "ksa.index"
    ];

    public const KSA_CREATE = [
        "name"    => "Create",
        "ability" => "ksa.create"
    ];

    public const KSA_EDIT = [
        "name"    => "Edit",
        "ability" => "ksa.edit",
    ];

    public const KSA_DELETE = [
        "name"    => "Delete",
        "ability" => "ksa.delete"
    ];

}
