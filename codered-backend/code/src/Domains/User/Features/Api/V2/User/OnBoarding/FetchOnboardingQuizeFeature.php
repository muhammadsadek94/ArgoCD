<?php

namespace App\Domains\User\Features\Api\V2\User\OnBoarding;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Repositories\GoalRepository;
use App\Domains\User\Repositories\ExperienceLevelRepository;
use App\Domains\Course\Repositories\CourseTagsRepositoryInterface;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;

class FetchOnboardingQuizeFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(Request $request, CourseCategoryRepositoryInterface $category_repository, CourseTagsRepositoryInterface $tags_repository, GoalRepository $goal_repository, ExperienceLevelRepository $experience_level_repository)
    {
        $categories = $category_repository->getActiveSubCategories();
        $tags = $tags_repository->getActiveTags();
        $goals = $goal_repository->getActiveGoals();
        $levels = $experience_level_repository->getLevels();

        return $this->run(RespondWithJsonJob::class, [
            "content" => compact('categories', 'tags', 'goals', 'levels')
        ]);
    }
}
