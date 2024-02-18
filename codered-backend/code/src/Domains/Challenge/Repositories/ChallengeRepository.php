<?php

namespace App\Domains\Challenge\Repositories;

use App\Domains\Challenge\Models\Challenge;
use App\Foundation\Repositories\Repository;
use App\Foundation\Repositories\RepositoryInterface;

class ChallengeRepository extends Repository implements RepositoryInterface
{
    public function __construct(Challenge $model)
    {
        parent::__construct($model);
    }

    public function getChallengeBySlug($slug)
    {
        return $this->model->where('slug', $slug)->orWhere('id', $slug)->active()->firstOrFail();
    }
}
