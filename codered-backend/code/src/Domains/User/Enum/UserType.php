<?php

namespace App\Domains\User\Enum;

use App\Foundation\BasicEnum;

class UserType extends BasicEnum {

    const USER = 1;
    const PROVIDER = 2;
    const PRO_ENTERPRISE_ADMIN = 3;
    const PRO_ENTERPRISE_SUBACCOUNT = 4;
    const REGULAR_ENTERPRISE_ADMIN = 5;
    const REGULAR_ENTERPRISE_SUBACCOUNT = 6;

}
