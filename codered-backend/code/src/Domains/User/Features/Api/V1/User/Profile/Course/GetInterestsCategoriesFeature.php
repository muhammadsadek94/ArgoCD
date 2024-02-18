<?php

namespace App\Domains\User\Features\Api\V1\User\Profile\Course;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;


class GetInterestsCategoriesFeature extends Feature
{

    public function handle(Request $request, CourseCategoryRepositoryInterface $category_repository)
    {
        $user = $request->user('api');

        $favorites_categories = $category_repository->getUserFavouriteCategories($user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'categories' => CourseCategoryResource::collection($favorites_categories)
            ]
        ]);
    }
}
