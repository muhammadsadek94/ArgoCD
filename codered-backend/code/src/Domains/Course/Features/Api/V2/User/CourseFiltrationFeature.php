<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Bundles\Repositories\BundlesRepository;
use App\Domains\Payments\Repositories\LearnPathInfoRepositoryInterface;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V2\CourseCollection;
use App\Domains\Course\Http\Resources\Api\V2\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V2\JobRoleResource;
use App\Domains\Course\Http\Resources\Api\V2\SpecialtyAreaResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\JobRoleRepositoryInterface;
use App\Domains\Course\Repositories\SpecialtyAreaRepositoryInterface;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathBasicInfoResource;

class CourseFiltrationFeature extends Feature
{

    const CACHE_TTL = 86400;

    public function handle(
        Request $request,
        CourseRepositoryInterface $course_repository,
        CourseCategoryRepositoryInterface $category_repository,
        CourseLevelRepository $course_level_repository,
        JobRoleRepositoryInterface $jobRoleRepository,
        SpecialtyAreaRepositoryInterface $specialtyAreaRepository,
        BundlesRepository $bundle_repository,
        LearnPathInfoRepositoryInterface $learn_repository
    )
    {

        //        Cache::forget('website_library_filtration');
        //        $request_as_string = http_build_query($request->toArray());
        //        return Cache::remember("website_library_filter_$request_as_string", SELF::CACHE_TTL, function () use($request, $category_repository, $course_level_repository, $course_repository, $jobRoleRepository, $specialtyAreaRepository, $bundle_repository, $learn_repository) {
        $courses = collect([]);
        $paths = collect([]);

        $filters = [
            'categories'     => CourseCategoryResource::collection($category_repository->getActiveSubCategories()),
            'levels'         => $course_level_repository->getLevels(),
            'job_role'       => JobRoleResource::collection($jobRoleRepository->getActiveJobRole()),
            'specialty_area' => SpecialtyAreaResource::collection($specialtyAreaRepository->getActiveSpecialtyArea()),
            'max_time'       => $course_repository->courseMaxTime(),
        ];
        $user = $request->user('api');
        $user_id = null;
        if ($user) {
            $user_id = $user->id;
            $user->load('all_course_enrollments');
            $user->load([
                'active_subscription' => function ($query) {
                    $query->with('package');
                }]);
        }

        $request->type = is_array($request->type) ? $request->type : [$request->type];

        if (!$request->has('type')) {
            $courses = $course_repository->courseFiltrationV2($request, user: $user);

            //            $microdegrees = $course_repository->getMicrodegreesV2($request);
            //            $bundles = $bundle_repository->getFeaturedBundlesV2($request);

            if ($courses->count() < 1) $courses = $this->getCourses(($course_repository));

            //            if ($microdegrees->count() < 1) $microdegrees = $this->getMicrodegrees($course_repository);

            //            if ($bundles->count() < 1) $bundles = $this->getBundles($learn_repository);

            if ($request->has('keyword'))
                $request->request->add(['courseIds' => $courses->pluck('id')]);

            $paths = $learn_repository->filtration($request, $user_id);

            //            if ($paths->count() < 1) $paths = $learn_repository->filtration($request, $user_id);
        }
        // if course has courses type
        if (($request->type && in_array('courses', $request->type))) {
            $courses = $course_repository->courseFiltrationV2($request);

            if ($courses->count() < 1) $courses = $this->getCourses(($course_repository));
        }

        // if request has microdegree type
        //        if (($request->type && in_array('microdegree', $request->type)) || !$request->has('type')) {
        //            $microdegrees = $course_repository->getMicrodegreesV2($request);
        //            if ($microdegrees->count() < 1) $microdegrees = $this->getMicrodegrees($course_repository);
        //        }

        // if request has learning path type
        if (($request->type && in_array('learn_path', $request->type))) {

            if (!$request->has('courseIds') && $request->has('keyword')) {
                if (isset($courses) && $courses->count() > 0)
                    $request->request->add(['courseIds' => $courses->pluck('id')]);
            }

            $paths = $learn_repository->filtration($request);
            $request->merge(['keyword' => '']);

            if ($paths->count() < 1) $paths = $learn_repository->filtration($request);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'result'      => count($courses) > 0 ? new CourseCollection($courses) : 0,
                // 'microdegree' => MicrodegreeInformationResource::collection($microdegrees ?? []),
                'microdegree' => [],
                // 'bundle' => BundlesBasicInformationResource::collection($bundles ?? []),
                'paths'       => count($paths) ? LearnPathBasicInfoResource::collection($paths) : [],
                'filters'     => $filters
            ]
        ]);

        //        });
    }

    private function getCourses($repo)
    {
        return $repo->getModel()->select('id', 'name', 'brief', 'level', 'timing', 'course_sub_category_id', 'course_category_id', 'course_type', 'image_id', 'slug_url', 'is_free', 'price', 'discount_price', 'agg_avg_reviews', 'agg_count_reviews', 'agg_count_course_enrollment', 'agg_lessons', 'agg_count_course_chapters')
            ->active()
            ->course()
            ->with(['image', 'category', 'cover'])
            ->orderBy('created_at', 'desc')->paginate(18);
    }

    private function getMicrodegrees($repo)
    {
        $microdegrees = $repo->getModel()->newQuery()->active()
            ->select('id', 'name', 'brief', 'level', 'timing', 'course_sub_category_id', 'course_category_id', 'course_type', 'image_id', 'slug_url', 'is_free', 'price', 'discount_price')
            ->Microdegrees()
            ->latest('created_at')->with(['image', 'category', 'cover'])->get();
        return $microdegrees;
    }

    private function getBundles($repo)
    {
        $bundles = $repo->getModel()->newQuery()->bundles()
            ->latest('created_at')
            ->active()
            ->get();
        return $bundles;
    }
}
