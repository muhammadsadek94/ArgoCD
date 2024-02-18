<?php

namespace App\Domains\User\Enum;

use App\Foundation\BasicEnum;

class UserActivation extends BasicEnum
{
    const SUSPEND = 0;
    const ACTIVE = 1;
    const PENDING = 2;
    const COMPLETE_PROFILE = 3;
    const WAITING_APPROVAL = 4;

}
