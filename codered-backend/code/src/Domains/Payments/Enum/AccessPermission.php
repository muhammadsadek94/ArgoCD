<?php

namespace App\Domains\Payments\Enum;

use App\Foundation\BasicEnum;

class AccessPermission extends BasicEnum
{
    const CONTENT_ONLY = 1;
    const FULL_CONTENT = 2;
    const CONTENT_WITH_LABS = 3;
    const CONTENT_WITH_VOUCHERS = 4;

}
