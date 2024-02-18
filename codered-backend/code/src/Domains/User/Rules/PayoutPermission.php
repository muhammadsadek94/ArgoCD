<?php

namespace App\Domains\User\Rules;

use App\Foundation\BasicEnum;

class PayoutPermission extends BasicEnum
{
    const MODULE = 'Payouts';

    const PAYOUT_INDEX = [
        "name"    => "Show Payouts",
        "ability" => "payout.index"
    ];

    const PAYOUT_CREATE = [
        "name"    => "Create",
        "ability" => "payout.create"
    ];

    const PAYOUT_EDIT = [
        "name"    => "Edit",
        "ability" => "payout.edit",
    ];

    const PAYOUT_DELETE = [
        "name"    => "DELETE",
        "ability" => "payout.delete",
    ];

    const PAYOUT_APPROVE = [
        "name"    => "Approve Payout",
        "ability" => "payout.approve",
    ];

    const PAYOUT_DISAPPROVE = [
        "name"    => "Disapprove Payout",
        "ability" => "payout.disapprove",
    ];
    const PAYOUT_PAID = [
        "name"    => "Paid Payout",
        "ability" => "payout.paid",
    ];
}
