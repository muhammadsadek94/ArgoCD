<?php

namespace App\Domains\User\Enum;

use App\Foundation\BasicEnum;

class SubscribeStatus extends BasicEnum {

    const ACTIVE = 1;
    const TRIAL = 2;
    const ENDED = 3;

}
