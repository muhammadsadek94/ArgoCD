<?php

namespace App\Domains\OpenApi\Features\Api\V2;

use App\Domains\Course\Models\Course;
use FontLib\TrueType\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\CourseInfoResourceCollection;

class AvailableCourseFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }

    public function handle(Request $request)
    {

        $user = $request->user('api');
        $courses = collect([]);
        $has_pro_access = $user->hasActiveSubscription(AccessType::PRO);
        $has_bundle_courses = $user->hasActiveSubscription(AccessType::COURSES);
        $has_bundle_categories = $user->hasActiveSubscription(AccessType::COURSE_CATEGORY);

        if ($has_pro_access) {
            $query = Course::getModel()->newQuery()->active()
                ->with(['image', 'category', 'cover']);
            $new_courses = $this->getFillterCourses($query, $request);
            $courses = $courses->merge($new_courses);
        }

        if (!empty($has_bundle_courses)) {
            $all_bundles = $user->active_subscription()
                ->whereHas('package', function ($query) {
                    return $query->where('access_type', AccessType::COURSES);
                })
                ->get();

            foreach ($all_bundles as $bundle) {
                $package = $bundle->package;
                $query = Course::getModel()->newQuery()->active()
                    ->with(['image', 'category', 'cover'])
                    ->latest()
                    ->where(function ($query) use ($package) {
                        return $query->whereIn('id', json_decode($package->access_id));
                    });
                $bundle_courses = $this->getFillterCourses($query, $request);
                $courses = $courses->merge($bundle_courses);
            }
        }

        $data = $this->paginate($courses);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'courses' => new CourseInfoResourceCollection ($data),
            ]
        ]);

    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ? : (Paginator::resolveCurrentPage() ? : 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    private function getFillterCourses($query, $request)
    {
        return $query->where('name', "LIKE", "%{$request->search}%")
            ->orWhere('brief', "LIKE", "%{$request->search}%")
            ->orWhere('description', "LIKE", "%{$request->search}%")
            ->get();

    }

}
