<?php

namespace App\Domains\Faq\Rules;

use App\Foundation\BasicEnum;

class FaqPermission extends BasicEnum
{
    const MODULE = 'Faq';

    const FAQ_INDEX = [
        "name"    => "Faq list",
        "ability" => "faq.index"
    ];

    const FAQ_CREATE = [
        "name"    => "Create",
        "ability" => "faq.create"
    ];

    const FAQ_EDIT = [
        "name"    => "Edit",
        "ability" => "faq.edit",
    ];

    const FAQ_DELETE = [
        "name"    => "Delete",
        "ability" => "faq.delete"
    ];

}
