<?php

namespace App\Domains\Course\Repositories;

use App\Domains\Course\Models\SpecialtyArea;
use App\Foundation\Repositories\Repository;

class SpecialtyAreaRepository extends Repository implements SpecialtyAreaRepositoryInterface
{
    public function __construct(SpecialtyArea $model) { parent::__construct($model); }

    public function getActiveSpecialtyArea(){
        return $this->model->select('id', 'name', 'activation')->active()->get();
    }
}
