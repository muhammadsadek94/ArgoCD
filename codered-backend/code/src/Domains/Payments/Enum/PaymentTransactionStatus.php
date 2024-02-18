<?php

namespace App\Domains\Payments\Enum;
use App\Foundation\BasicEnum;

class PaymentTransactionStatus extends BasicEnum
{
    const NONE = 0;
    const ACTIVE = 1;
    const CANCELLED = 2;
    const REFUNDED = 3;

}