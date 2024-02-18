<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use App\Domains\Course\Http\Resources\Api\V2\CourseCategoryWithCoursesResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathInfoResource;
use App\Domains\User\Repositories\UserRepository;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class ProUserHomeFeature extends Feature
{

    public function __construct(){}

    public function handle(
        Request $request,
        UserRepository $user_repository,
        CourseCategoryRepositoryInterface $category_repository
        )
    {
        $user = $request->user();

        $favorites_categories = $category_repository->getUserFavouriteCategories($request->user());

        $learn_paths = $user_repository->getLearnPathsCateogryBased($user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'favorites_categories'          => CourseCategoryWithCoursesResource::collection($favorites_categories),
                'learn_paths'                   => LearnPathInfoResource::collection($learn_paths),
            ]
        ]);
    }


}
