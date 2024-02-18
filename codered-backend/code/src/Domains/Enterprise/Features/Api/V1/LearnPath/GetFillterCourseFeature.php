<?php

namespace App\Domains\Enterprise\Features\Api\V1\LearnPath;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseCollection;
use App\Domains\Course\Models\Lookups\CourseTag;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseDetailsResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseLearnPathsDetailsResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseLearnPathsResource;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use App\Foundation\Traits\Authenticated;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\ResourceCollection;


class GetFillterCourseFeature extends Feature
{
use Authenticated;
    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(Request $request, CourseRepositoryInterface $course_repository, CourseCategoryRepositoryInterface $category_repository, CourseLevelRepository $course_level_repository)

    {
        $admin = $this->auth('api');

        $filters = [
            'categories' => CourseCategoryResource::collection($category_repository->getActiveSubCategories()),
            'levels' => $course_level_repository->getLevels(),
            'tags' =>  CourseTag::where('activation' , 1)->limit(20)->get(),

        ];
        $courses = $course_repository->courseFiltrationLearnPath($request,$request->has('perPage')? $request->perPage : 20) ;
        $learnPaths = PackageSubscription::active()->where('id',$request->learn_path)->where(function ($query) use ($admin) {
            $query->whereNull('enterprise_id')
                ->orWhere('enterprise_id', $admin->id);
        })->first();
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'result'  => new CourseCollection($courses),
                'filters' => $filters,
                'learnPaths' => new EnterpriseLearnPathsDetailsResource($learnPaths)
            ]
        ]);
    }
}
