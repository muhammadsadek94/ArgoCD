<?php

namespace App\Domains\OpenApi\Features\Api\V1;

use App\Domains\Course\Models\Course;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use FontLib\TrueType\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\OpenApi\Http\Resources\Api\V1\User\CourseInfoResourceCollection;

class AvailableCourseFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }

    public function handle(Request $request, CourseRepositoryInterface $course_repository)
    {

        $user = $request->user('api');
        $courses = $course_repository->recommendCoursesDependingSubscriptions($user, 30);
        // $data = $this->paginate($courses, 99, $request->page);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'courses' => new CourseInfoResourceCollection ($courses),
            ]
        ]);

    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ? : (Paginator::resolveCurrentPage() ? : 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new LengthAwarePaginator($items->values()->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    private function getFillterCourses($query, $request)
    {
        return $query->where('name', "LIKE", "%{$request->search}%")
            ->orWhere('brief', "LIKE", "%{$request->search}%")
            ->orWhere('description', "LIKE", "%{$request->search}%")
            ->get();

    }

}
