<?php

namespace App\Domains\Partner\Repositories;

use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Partner\Repositories\Interfaces\CourseCategoryRepositoryInterface;
use App\Foundation\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;

class CourseCategoryRepository extends Repository implements CourseCategoryRepositoryInterface
{
    public function __construct(CourseCategory $model) { parent::__construct($model); }

    /**
     * @return Collection
     */
    public function getActiveCategories() :Collection
    {
        return $this->model->active()->latest('created_at')->get();
    }

}
