<?php

namespace App\Domains\Payments\Features\Api\V2\User;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Payments\Http\Resources\Api\V2\User\PackageResource;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;

class GetSubscriptionPackagesFeature extends Feature
{
    public function handle(Request $request, PackageSubscriptionRepositoryInterface $package_subscription_repository)
    {
        $user = $request->user('api');
        $user_packages = $user->subscriptions()
            ->whereHas('package')
            ->with('package')
            ->where('expired_at', ">", now())
            ->with([
                'courseEnrollments' => function ($query) use ($user) {
                    $query->where('user_id', $user->id)->with('course');
                }])
            ->latest()
            ->groupBy('package_id')
            ->distinct()
            ->get();

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'pro_packages' => PackageResource::collection($user_packages),
            ]
        ]);
    }
}
