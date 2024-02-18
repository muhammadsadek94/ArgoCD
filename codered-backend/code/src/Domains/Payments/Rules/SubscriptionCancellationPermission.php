<?php

namespace App\Domains\Payments\Rules;

use App\Foundation\BasicEnum;

class SubscriptionCancellationPermission extends BasicEnum
{
    const MODULE = 'Subscription Cancellation Requests';

    const SUBSCRIPTION_CANCELLATION_INDEX = [
        "name"    => "Show Cancellation Requests",
        "ability" => "subscription_cancellation_request.index"
    ];

    const SUBSCRIPTION_CANCELLATION_EDIT = [
        "name"    => "Update request Status",
        "ability" => "subscription_cancellation_request.edit",
    ];

    const SUBSCRIPTION_CANCELLATION_DELETE = [
        "name"    => "Delete",
        "ability" => "subscription_cancellation_request.delete"
    ];

}
