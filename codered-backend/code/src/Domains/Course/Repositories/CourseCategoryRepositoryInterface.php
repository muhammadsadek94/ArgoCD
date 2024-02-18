<?php

namespace App\Domains\Course\Repositories;

use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Foundation\Repositories\RepositoryInterface;

interface CourseCategoryRepositoryInterface extends RepositoryInterface
{
    public function getActiveSubCategories() :Collection;
    public function getActiveCategories() :Collection;
    public function getUserFavouriteCategories(?User $user = null);
}
