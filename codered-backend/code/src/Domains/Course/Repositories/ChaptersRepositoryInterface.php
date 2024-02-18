<?php

namespace App\Domains\Course\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Foundation\Repositories\RepositoryInterface;

interface ChaptersRepositoryInterface extends RepositoryInterface
{
    /**
     * @param $course_id
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function getActiveChaptersByCourseId($course_id);
}