<?php

namespace App\Domains\OpenApi\Features\Api\V2\User\Subscription;

use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\OpenApi\Http\Requests\Api\V2\User\SubscribeRequest;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\SubscriptionResource;
use App\Domains\User\Enum\SubscribeStatus;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class SubscribeFeature extends Feature
{

    public function handle(SubscribeRequest $request)
    {
        $user = $request->user('api');
        $package_id = $request->package_id;
        $subscription_id = $request->subscription_id;

        $package = PackageSubscription::find($package_id);
        $subscription = $user->subscriptions()->where('subscription_id', $subscription_id)->first();

        $paid_amount = (float) 0;
        $status = SubscribeStatus::ACTIVE;

        $duration = $package->type == SubscriptionPackageType::CUSTOM ? now()->addDays($package->duration) :
            ($package->type == SubscriptionPackageType::MONTHLY ? now()->addMonth() : now()->addYear());

        if (empty($subscription)) {
            $subscription = $user->subscriptions()->create([
                'expired_at'      => $duration,
                'status'          => $status,
                'subscription_id' => $subscription_id,
                'package_id'      => $package->id
            ]);
        } else {
            $subscription->status = SubscribeStatus::ACTIVE;
            $subscription->expired_at = $duration;
            $subscription->save();
        }

        return $this->run(RespondWithJsonJob::class, [
            'content' => new SubscriptionResource($subscription)
        ]);
    }
}
