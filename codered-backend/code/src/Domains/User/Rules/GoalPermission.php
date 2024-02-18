<?php

namespace App\Domains\User\Rules;

use App\Foundation\BasicEnum;

class GoalPermission extends BasicEnum
{
    const MODULE = 'Goals';

    const GOAL_INDEX = [
        "name"    => "Show Goals",
        "ability" => "goal.index"
    ];

    const GOAL_CREATE = [
        "name"    => "Create",
        "ability" => "goal.create"
    ];

    const GOAL_EDIT = [
        "name"    => "Edit",
        "ability" => "goal.edit",
    ];

    const GOAL_DELETE = [
        "name"    => "Delete",
        "ability" => "goal.delete"
    ];

}
