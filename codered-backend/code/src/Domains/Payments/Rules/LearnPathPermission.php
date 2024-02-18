<?php

namespace App\Domains\Payments\Rules;

use App\Foundation\BasicEnum;

class LearnPathPermission extends BasicEnum
{
    const MODULE = 'Learnpaths ';

    const LEARN_PATH_INDEX = [
        "name"    => "Show Learnpaths",
        "ability" => "Learnpath.index"
    ];

    const LEARN_PATH_CREATE = [
        "name"    => "Create Learnpath",
        "ability" => "Learnpath.create"
    ];

    const LEARN_PATH_EDIT = [
        "name"    => "Edit",
        "ability" => "Learnpath.edit",
    ];

    const LEARN_PATH_DELETE = [
        "name"    => "Delete",
        "ability" => "Learnpath.delete"
    ];

}
