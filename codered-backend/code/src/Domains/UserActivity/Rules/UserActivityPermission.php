<?php

namespace App\Domains\UserActivity\Rules;

use App\Foundation\BasicEnum;

class UserActivityPermission extends BasicEnum
{
    const MODULE = 'User Activities';

    const USER_ACTIVITY_INDEX = [
        "name"    => "View All Activities",
        "ability" => "user_activity.index"
    ];


}
