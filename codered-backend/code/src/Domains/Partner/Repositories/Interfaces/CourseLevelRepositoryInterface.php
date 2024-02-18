<?php

namespace App\Domains\Partner\Repositories\Interfaces;

use App\Foundation\Repositories\RepositoryInterface;
use Illuminate\Support\Collection;

interface CourseLevelRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection
     */
    public static function getLevels(): Collection;
}
