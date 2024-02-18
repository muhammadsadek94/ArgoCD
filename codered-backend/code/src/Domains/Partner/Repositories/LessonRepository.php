<?php

namespace App\Domains\Partner\Repositories;

use App\Domains\Course\Models\Lesson;
use App\Domains\Partner\Repositories\Interfaces\LessonRepositoryInterface;
use App\Foundation\Repositories\Repository;

class LessonRepository extends Repository implements LessonRepositoryInterface
{
    public function __construct(Lesson $model)
    {
        parent::__construct($model);
    }
}
