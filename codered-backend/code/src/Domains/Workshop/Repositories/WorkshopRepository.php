<?php

namespace App\Domains\Workshop\Repositories;

use App\Domains\Workshop\Enum\WorkshopType;
use App\Domains\Workshop\Repositories\Interfaces\WorkshopRepositoryInterface;
use App\Domains\Workshop\Models\Workshop;
use App\Foundation\Repositories\Repository;
use Carbon\Carbon;
use DB;

class WorkshopRepository extends Repository implements WorkshopRepositoryInterface
{
    public function __construct(Workshop $model)
    {
        parent::__construct($model);
    }


    public function getWorkshops()
    {
        $workshops = $this->getModel()
            ->active()
            ->get();

        return $workshops;
    }

   public function getRecordedWorkshops() {
        $workshops = $this->getModel()
            ->active()
            ->where('type', WorkshopType::RECORDED)
            ->get();

        return $workshops;
   }

   public function getUpcomingWorkshops() {
    $workshops = $this->getModel()
        ->active()
        ->where('type', WorkshopType::UPCOMING)
        ->get();

    return $workshops;
}
 
    public function getWorkshopById($id)
    {
        $now = Carbon::now()->addHours(2);
        $date = $now->format('Y-m-d G:i:s');
        $workshop = $this->getModel()
        ->active()
        ->where('id', $id)
        ->where(DB::raw("CONCAT(`date`, ' ', `time`)"), '>=', $date)
        ->first();
        
        return $workshop;   
    }
   
    
     

}
