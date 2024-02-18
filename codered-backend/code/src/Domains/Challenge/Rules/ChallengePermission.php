<?php

namespace App\Domains\Challenge\Rules;

use App\Foundation\BasicEnum;

class ChallengePermission extends BasicEnum
{
    const MODULE = ' Competitions Management';

    const CHALLENGE_INDEX = [
        "name"    => " Competitions list",
        "ability" => "challenge.index"
    ];

    const CHALLENGE_CREATE = [
        "name"    => "Create",
        "ability" => "challenge.create"
    ];

    const CHALLENGE_EDIT = [
        "name"    => "Edit",
        "ability" => "challenge.edit",
    ];

    const CHALLENGE_DELETE = [
        "name"    => "Delete",
        "ability" => "challenge.delete"
    ];

}
