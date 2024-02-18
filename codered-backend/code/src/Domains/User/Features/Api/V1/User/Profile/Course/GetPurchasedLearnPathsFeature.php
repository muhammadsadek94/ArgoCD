<?php

namespace App\Domains\User\Features\Api\V1\User\Profile\Course;

use App\Domains\Configuration\Repositories\PackageSubscriptionRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\Microdegree\PackageResource;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class GetPurchasedLearnPathsFeature extends Feature
{

    public function handle(Request $request, PackageSubscriptionRepositoryInterface $package_repository)
    {
        $user = $request->user('api');

        $learn_paths = $package_repository->getPurchasedLearnPaths($user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'purchased_learn_paths' => PackageResource::collection($learn_paths)
            ]
        ]);
    }
}
