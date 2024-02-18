<?php

namespace App\Domains\Course\Repositories;

use Illuminate\Http\Request;
use App\Domains\User\Models\User;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Enum\CourseType;
use App\Foundation\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;

class ChaptersRepository extends Repository implements ChaptersRepositoryInterface
{
    public function __construct(Chapter $model) { parent::__construct($model); }

    /**
     * @param $course_id
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function getActiveChaptersByCourseId($course_id)
    {
        return $this->getModel()->newQuery()->active()->where('course_id', $course_id)->get();
    }
}