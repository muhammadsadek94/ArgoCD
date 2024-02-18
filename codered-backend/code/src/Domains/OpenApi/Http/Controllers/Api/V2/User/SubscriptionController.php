<?php

namespace App\Domains\OpenApi\Http\Controllers\Api\V2\User;

use App\Domains\OpenApi\Features\Api\V2\User\Subscription\RevokeSubscriptionFeature;
use App\Domains\OpenApi\Features\Api\V2\User\Subscription\SubscribeFeature;
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
