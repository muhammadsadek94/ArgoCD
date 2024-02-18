<?php

namespace App\Domains\Course\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface CourseTagsRepositoryInterface
{
    public function getActiveTags();
    public function getFilterCourseTag(int $limit = 20, $search = null): Collection;

}
