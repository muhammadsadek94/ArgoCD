<?php

namespace App\Domains\Partner\Repositories;

use App\Domains\Course\Models\Course;
use App\Domains\Partner\Models\Partner;
use App\Domains\Partner\Repositories\Interfaces\CourseRepositoryInterface;
use App\Foundation\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


class CourseRepository extends Repository implements CourseRepositoryInterface
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    /**
     * @param Request $request
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function courseFiltration(Request $request, Partner $partner, int $perPage = 20): LengthAwarePaginator
    {
        $allowed_courses_ids = $partner->courses()->pluck('courses.id');
        $search = $this->getModel()->newQuery()->whereIn('id', $allowed_courses_ids);
        $search = $search->active()->course()->with(['image', 'category', 'cover']);

        $search = $search->when($request->search, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->where('name', "LIKE", "%{$request->search}%")
                    ->orWhere('brief', "LIKE", "%{$request->search}%")
                    ->orWhere('description', "LIKE", "%{$request->search}%")
                    ->orWhereHas('tags', function ($query) use ($request) {
                        return $query->where('course_tags.name', "LIKE", "%{$request->search}%");
                    });
            });
        });

        $search = $search->when($request->course_category_id, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->whereIn('course_category_id', $request->course_category_id);
            });
        });

        $search = $search->when($request->level, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->whereIn('level', $request->level);
            });
        });

        $search = $search->when($request->has('start_timing') && $request->has('end_timing'), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $query->whereBetween('timing', [(int)$request->start_timing, (int)$request->end_timing]);
            });
        });

        /** sortings
         * Best match [by tags] -> best_match
         * duration longest to short, short to longest -> timing
         * new arrived -> created_at
         */
        $search = $search->when($request->order_by && $request->order_direction, function ($query) use ($request) {
            return $query->orderBy($request->order_by, $request->order_direction);
        });

        return $search->paginate($perPage);
    }

    /**
     * @param string|null $partner_id
     * @return Model|null
     */
    public function findCourseById(string $partner_id, string $course_id): ?Model
    {
        return $this->getModel()->newQuery()
            ->active()->course()
            ->whereHas('partners', function ($query) use ($partner_id) {
                $query->where('partners.id', $partner_id);
            })
            ->with(['chapters', 'lessons'])->find($course_id);
    }
}
