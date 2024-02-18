<?php

namespace App\Domains\Payments\Features\Api\V1\User;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Payments\Http\Resources\Api\V1\User\PackageResource;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;


class GetSubscriptionPackagesFeature extends Feature
{
    public function handle(Request $request, PackageSubscriptionRepositoryInterface $package_subscription_repository)
    {
        $user = $request->user('api');
        $pro_packages = $package_subscription_repository->getUserActiveSubscription($user);

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'pro_packages' => PackageResource::collection($pro_packages),
            ]
        ]);
    }
}
