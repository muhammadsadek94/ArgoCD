<?php

namespace App\Domains\User\Rules;

use App\Foundation\BasicEnum;

class UserPermission extends BasicEnum
{
    const MODULE = 'User';

    const USER_INDEX = [
        "name"    => "Users list",
        "ability" => "user.index"
    ];

    const USER_CREATE = [
        "name"    => "Create",
        "ability" => "user.create"
    ];

    const USER_EDIT = [
        "name"    => "Edit",
        "ability" => "user.edit",
    ];

    const USER_SHOW = [
        "name"    => "Show User Profile",
        "ability" => "user.show",
    ];

    const USER_DELETE = [
        "name"    => "Delete",
        "ability" => "user.delete"
    ];

    const LOGGED_AS_USER = [
        "name"    => "Logged as specific user in frontend",
        "ability" => "user.logged_in"
    ];



}
