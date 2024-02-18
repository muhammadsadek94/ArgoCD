<?php

namespace App\Domains\OpenApi\Enum;

use App\Foundation\BasicEnum;

class AccessScope extends BasicEnum
{
    const UPDATE_SUBSCRIPTIONS = 'update_subscriptions';
    const REVOKE_SUBSCRIPTIONS = 'revoke_subscriptions';
}
