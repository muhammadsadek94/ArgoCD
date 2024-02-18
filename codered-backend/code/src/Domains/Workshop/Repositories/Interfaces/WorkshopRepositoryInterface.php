<?php

namespace App\Domains\Workshop\Repositories\Interfaces;

use App\Foundation\Repositories\RepositoryInterface;

interface WorkshopRepositoryInterface extends RepositoryInterface
{


    /**
     * get promocode details
     * @return mixed
     */
    public function getRecordedWorkshops();

    public function getUpcomingWorkshops();

    public function getWorkshopById($id);

}
