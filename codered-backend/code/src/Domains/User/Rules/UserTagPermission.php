<?php

namespace App\Domains\User\Rules;

use App\Foundation\BasicEnum;

class UserTagPermission extends BasicEnum
{
    const MODULE = 'User Tags';

    const USER_TAG_INDEX = [
        "name"    => "Show User tags list",
        "ability" => "user_tag.index"
    ];

    const USER_TAG_CREATE = [
        "name"    => "Create",
        "ability" => "user_tag.create"
    ];

    const USER_TAG_EDIT = [
        "name"    => "Edit",
        "ability" => "user_tag.edit",
    ];

    const USER_TAG_DELETE = [
        "name"    => "Delete",
        "ability" => "user_tag.delete"
    ];

}
