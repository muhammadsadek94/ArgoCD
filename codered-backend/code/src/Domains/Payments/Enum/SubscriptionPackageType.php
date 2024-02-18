<?php

namespace App\Domains\Payments\Enum;

use App\Foundation\BasicEnum;

class SubscriptionPackageType extends BasicEnum
{
    const MONTHLY = 1;  //PRO
    const ANNUAL = 2;   //PRO
    const CUSTOM = 3;
    const Enterprise = 4;
    const learnPath = 5;
}
