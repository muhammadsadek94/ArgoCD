<?php

namespace App\Domains\Partner\Repositories\Interfaces;

use App\Foundation\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface CourseCategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection
     */
    public function getActiveCategories() :Collection;

}
