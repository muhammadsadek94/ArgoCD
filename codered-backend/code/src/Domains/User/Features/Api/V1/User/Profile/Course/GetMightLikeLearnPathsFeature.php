<?php

namespace App\Domains\User\Features\Api\V1\User\Profile\Course;

use App\Domains\Configuration\Repositories\PackageSubscriptionRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\Microdegree\PackageResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class GetMightLikeLearnPathsFeature extends Feature
{

    public function handle(Request $request, PackageSubscriptionRepositoryInterface $package_repository, CourseCategoryRepositoryInterface $category_repository)
    {
        $user = $request->user('api');

        $favorites_categories = $category_repository->getUserFavouriteCategories($user);
        $learn_paths = $package_repository->getLearnPathsBasedOnCategories($favorites_categories->pluck('id')->toArray());

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'might_like_learn_paths' => PackageResource::collection($learn_paths)
            ]
        ]);
    }
}
