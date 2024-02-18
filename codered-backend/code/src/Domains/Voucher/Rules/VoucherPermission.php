<?php

namespace App\Domains\Voucher\Rules;

use App\Foundation\BasicEnum;

class VoucherPermission extends BasicEnum
{
    const MODULE = 'Vouchers';

    const VOUCHER_INDEX = [
        "name"    => "Show Vouchers",
        "ability" => "voucher.index"
    ];

    const VOUCHER_CREATE = [
        "name"    => "Create",
        "ability" => "voucher.create"
    ];


    const VOUCHER_DELETE = [
        "name"    => "Delete",
        "ability" => "voucher.delete"
    ];

}
