<?php

namespace App\Domains\Course\Features\Api\V1\User;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;


class GetLibraryFeature extends Feature
{

    public function handle(Request $request, CourseRepositoryInterface $course_repository, CourseCategoryRepositoryInterface $category_repository, CourseLevelRepository $course_level_repository)
    {
        $filters = [
            'categories' => CourseCategoryResource::collection($category_repository->getActiveCategories()),
            'levels' => $course_level_repository->getLevels(),
        ];

        $recommended_courses = $course_repository->getRecommendedCourses($request->user());
        $recently_added_courses = $course_repository->getRecentlyAddedCourses();
        $most_popular_courses = $course_repository->getFeaturedCourses();
        $coming_soon = $course_repository->getComingSoonCourses();
        $favorites_categories = $category_repository->getActiveCategories();
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'favorites_categories'   => CourseCategoryResource::collection($favorites_categories),
                'recommended_courses'    => CourseBasicInfoResource::collection($recommended_courses),
                'recently_added_courses' => CourseBasicInfoResource::collection($recently_added_courses),
                'most_popular_courses'   => CourseBasicInfoResource::collection($most_popular_courses),
                'most_coming_soon'       => CourseBasicInfoResource::collection($coming_soon),
                'filters' => $filters
            ]
        ]);
    }
}
