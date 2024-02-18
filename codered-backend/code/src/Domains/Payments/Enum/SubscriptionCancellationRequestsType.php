<?php

namespace App\Domains\Payments\Enum;

use App\Foundation\BasicEnum;

class SubscriptionCancellationRequestsType extends BasicEnum {

    const OPTION_1 = 1;
    const OPTION_2 = 2;
    const OPTION_3 = 3;
    const OPTION_4 = 4;
    const OTHERS = 5;

    public static function getReadableType($type = self::OTHERS) {
        $types = [
            self::OPTION_1 => 'i dont have time to learn new skills',
            self::OPTION_2 => 'the content on the platform doesn\'t fit my needs',
            self::OPTION_3 => 'the content is so overwhelming. i dont know where to start',
            self::OPTION_4 => 'i cannot afford to pay for the subscription anymore',
            self::OTHERS => 'others',
        ];

        return $types[$type] ?? 'Unknown Type';

    }
}
