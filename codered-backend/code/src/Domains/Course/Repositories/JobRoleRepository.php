<?php

namespace App\Domains\Course\Repositories;

use App\Domains\Course\Models\JobRole;
use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use App\Domains\Course\Models\Lookups\CourseCategory;

class JobRoleRepository extends Repository implements JobRoleRepositoryInterface
{
    public function __construct(JobRole $model) { parent::__construct($model); }

    public function getActiveJobRole(){
        return $this->model->select('id', 'name', 'activation')->active()->get();

    }



}
