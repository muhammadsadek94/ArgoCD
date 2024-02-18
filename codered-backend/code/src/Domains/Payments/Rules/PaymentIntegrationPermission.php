<?php

namespace App\Domains\Payments\Rules;

use App\Foundation\BasicEnum;

class PaymentIntegrationPermission extends BasicEnum
{
    const MODULE = 'Payment Integration';

    const PAYMENT_INTEGRATION_INDEX = [
        "name"    => "Show Payment Integrations",
        "ability" => "payment_integration.index"
    ];

    const PAYMENT_INTEGRATION_CREATE = [
        "name"    => "Create",
        "ability" => "payment_integration.create"
    ];

    const PAYMENT_INTEGRATION_EDIT = [
        "name"    => "Edit",
        "ability" => "payment_integration.edit",
    ];

    const PAYMENT_INTEGRATION_DELETE = [
        "name"    => "Delete",
        "ability" => "payment_integration.delete"
    ];

}
