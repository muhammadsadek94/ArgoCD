<?php

namespace App\Domains\Course\Repositories;

use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use App\Domains\Course\Models\Lookups\CourseCategory;
use Framework\Traits\SelectColumnTrait;

class CourseCategoryRepository extends Repository implements CourseCategoryRepositoryInterface
{
    use SelectColumnTrait;

    public function __construct(CourseCategory $model)
    {
        parent::__construct($model);
    }

    public function getActiveCategories(): Collection
    {
        return $this->model->active()->parent()->latest('created_at')->get();
    }


    public function getActiveSubCategories(): Collection
    {
        return $this->model->select(SelectColumnTrait::$categoryColumns)->active()->child()->latest('created_at')->get();
    }

    public function getUserFavouriteCategories(?User $user = null)
    {
        //        return $this->getActiveCategories();

        if (empty($user)) return collect([]);

        return $user->categories()
            ->active()
            ->latest('created_at')
            ->get();
    }
}
