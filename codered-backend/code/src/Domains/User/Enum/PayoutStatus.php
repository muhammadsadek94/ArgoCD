<?php

namespace App\Domains\User\Enum;

use App\Foundation\BasicEnum;

class PayoutStatus extends BasicEnum {

    const PAID = 1;
    const PENDING = 2;
    const DISAPPROVE = 3;
    const APPROVED = 4;

    public static function getName($type) {
        $types = [
            self::PAID => [
                "name" => "Paid"
            ],
            self::PENDING => [
                "name" => "Pending"
            ],
            self::DISAPPROVE => [
                "name" => "Disapproved"
            ],
            self::APPROVED => [
                "name" => "Approved"
            ],
        ];

        return  @$types[$type]['name'];
    }

    public static function getStatusList()
    {
        return [
            self::PAID          => 'Paid',
            self::PENDING       => 'Pending',
            self::DISAPPROVE    => 'Disapproved',
            self::APPROVED      => 'Approved',
        ];
    }
}
