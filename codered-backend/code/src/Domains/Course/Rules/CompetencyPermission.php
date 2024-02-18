<?php

namespace App\Domains\Course\Rules;

use App\Foundation\BasicEnum;

class CompetencyPermission extends BasicEnum
{
    public const MODULE = 'Competency';

    public const COMPETENCY_INDEX = [
        "name"    => "Competencies list",
        "ability" => "competency.index"
    ];

    public const COMPETENCY_CREATE = [
        "name"    => "Create",
        "ability" => "competency.create"
    ];

    public const COMPETENCY_EDIT = [
        "name"    => "Edit",
        "ability" => "competency.edit",
    ];

    public const COMPETENCY_DELETE = [
        "name"    => "Delete",
        "ability" => "competency.delete"
    ];

}
