<?php

namespace App\Domains\OpenApi\Http\Controllers\Api\V1\User;

use App\Domains\OpenApi\Features\Api\V1\User\Subscription\RevokeSubscriptionFeature;
use App\Domains\OpenApi\Features\Api\V1\User\Subscription\SubscribeFeature;
use INTCore\OneARTFoundation\Http\Controller;

class SubscriptionController extends Controller
{

    public function subscribe()
    {
        return $this->serve(SubscribeFeature::class);
    }

    public function revoke()
    {
        return $this->serve(RevokeSubscriptionFeature::class);
    }


}
