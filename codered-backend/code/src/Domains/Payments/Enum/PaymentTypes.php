<?php

namespace App\Domains\Payments\Enum;

use App\Foundation\BasicEnum;

class PaymentTypes extends BasicEnum
{
    const SAMCART = 1;
    const RAZORPAY = 2;
}
