<?php

namespace App\Domains\Payments\Enum;

use App\Foundation\BasicEnum;

class SubscriptionCancellationRequestsStatus extends BasicEnum {

    const NEW = 1;
    const CANCELLED = 2;

    public static function getReadableStatus($status = self::NEW) {
        $status_human = [
            self::NEW => 'New',
            self::CANCELLED => 'Cancelled',
        ];

        return $status_human[$status] ?? 'Unknown Type';

    }
}
