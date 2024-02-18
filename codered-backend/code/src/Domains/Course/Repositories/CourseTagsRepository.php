<?php

namespace App\Domains\Course\Repositories;

use App\Foundation\Repositories\Repository;
use App\Domains\Course\Models\Lookups\CourseTag;
use Illuminate\Database\Eloquent\Collection;

class CourseTagsRepository extends Repository implements CourseTagsRepositoryInterface
{
    public function __construct(CourseTag $model) { parent::__construct($model); }

    public function getActiveTags()
    {
        return $this->model->active()->withCount('courses')->get()->sortByDesc('courses_count')->values();
//        return $this->model->active()->has('courses', '>',1)->withCount('courses')->get()->sortByDesc('courses_count')->values();
    }


    public function getFilterCourseTag(int $limit = 20, $search = null): Collection
    {
        $query = $this->model->active();
        if ($search) {
            $query = $query->where('name', "LIKE", "%{$search}%");
        }
        $query = $query->limit($limit);
        return $query->get();
    }
}
