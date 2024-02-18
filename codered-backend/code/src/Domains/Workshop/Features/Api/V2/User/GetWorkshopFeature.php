<?php

namespace App\Domains\Workshop\Features\Api\V2\User;

use App\Domains\Workshop\Http\Resources\V2\WorkshopResource;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Workshop\Repositories\Interfaces\WorkshopRepositoryInterface;

class GetWorkshopFeature extends Feature
{

    public function handle(Request $request, WorkshopRepositoryInterface $workshop_repository)
    {


        $recorded_workshops       = $workshop_repository->getRecordedWorkshops();
        $upcoming_workshops      = $workshop_repository->getUpcomingWorkshops();


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'recorded_workshops' => WorkshopResource::collection($recorded_workshops),
                'upcoming_workshops' => WorkshopResource::collection($upcoming_workshops),
            ]
        ]);
    }
}
