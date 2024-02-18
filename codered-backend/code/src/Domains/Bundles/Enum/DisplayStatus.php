<?php

namespace App\Domains\Bundles\Enum;

use App\Foundation\BasicEnum;

class DisplayStatus extends BasicEnum
{
    const LATEST = 1;      //for new
    const SALE = 2;
    const FEATURED = 3;

}
