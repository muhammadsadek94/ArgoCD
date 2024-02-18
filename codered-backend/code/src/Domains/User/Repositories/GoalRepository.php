<?php


namespace App\Domains\User\Repositories;


use App\Domains\User\Models\User;
use App\Domains\User\Models\Lookups\Goal;
use App\Foundation\Repositories\Repository;
use App\Foundation\Repositories\RepositoryInterface;

class GoalRepository extends Repository implements RepositoryInterface
{
    public function __construct(Goal $model) { parent::__construct($model); }

    public function getActiveGoals()
    {
        return $this->model->active()->latest('created_at')->get();
    }

}