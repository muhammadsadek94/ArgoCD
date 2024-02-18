<?php

namespace App\Domains\Partner\Rules;

use App\Foundation\BasicEnum;

class PartnerPermissions extends BasicEnum
{
    const MODULE = 'Partner';

    const PARTNER_INDEX = [
        "name"    => "Partner list",
        "ability" => "partner.index"
    ];

    const PARTNER_CREATE = [
        "name"    => "Create",
        "ability" => "partner.create"
    ];

    const PARTNER_EDIT = [
        "name"    => "Edit",
        "ability" => "partner.edit",
    ];

    const PARTNER_DELETE = [
        "name"    => "Delete",
        "ability" => "partner.delete"
    ];

}
