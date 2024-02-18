<?php

namespace App\Domains\Voucher\Http\Controllers\Api\V2\User;

use App\Domains\Voucher\Features\Api\V2\User\Profile\UseVoucherFeature;
use INTCore\OneARTFoundation\Http\Controller;

class VoucherController extends Controller
{
    public function postVoucher()
    {
        return $this->serve(UseVoucherFeature::class);
    }

}



