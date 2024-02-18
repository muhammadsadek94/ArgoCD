<?php

namespace App\Domains\Blog\Rules;

use App\Foundation\BasicEnum;

class QuotePermission extends BasicEnum
{
    const MODULE = 'Quote';

    const QUOTE_INDEX = [
        "name"    => "Quote list",
        "ability" => "quote.index"
    ];

    const QUOTE_CREATE = [
        "name"    => "Create",
        "ability" => "quote.create"
    ];

    const QUOTE_EDIT = [
        "name"    => "Edit",
        "ability" => "quote.edit",
    ];

    const QUOTE_DELETE = [
        "name"    => "Delete",
        "ability" => "quote.delete"
    ];

}
