<?php

namespace App\Domains\OpenApi\Features\Api\V1\User\Subscription;

use App\Domains\OpenApi\Http\Requests\Api\V1\User\RevokeSubscribeRequest;
use App\Domains\OpenApi\Http\Resources\Api\V1\User\SubscriptionResource;
use App\Domains\User\Enum\SubscribeStatus;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class RevokeSubscriptionFeature extends Feature
{

    public function handle(RevokeSubscribeRequest $request)
    {
        $user = $request->user('api');
        $subscription_id = $request->subscription_id;

        $subscription = $user->subscriptions()->where('subscription_id', $subscription_id)->first();

        if (!empty($subscription)) {
            $subscription->update([
                'status' => SubscribeStatus::ENDED,
            ]);
        }

        $subscription = $subscription->fresh();

        return $this->run(RespondWithJsonJob::class, [
            'content' => new SubscriptionResource($subscription)
        ]);
    }
}
