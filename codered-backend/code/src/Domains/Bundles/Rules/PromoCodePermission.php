<?php

namespace App\Domains\Bundles\Rules;

use App\Foundation\BasicEnum;

class PromoCodePermission extends BasicEnum
{
    const MODULE = 'PROMO CODE';

    const PROMO_CODE_INDEX = [
        "name"    => "Promo Code list",
        "ability" => "promo_code.index"
    ];

    const PROMO_CODE_CREATE = [
        "name"    => "Create",
        "ability" => "promo_code.create"
    ];

    const PROMO_CODE_EDIT = [
        "name"    => "Edit",
        "ability" => "promo_code.edit",
    ];

    const PROMO_CODE_DELETE = [
        "name"    => "Delete",
        "ability" => "promo_code.delete"
    ];

}
