<?php

namespace App\Domains\Workshop\Features\Api\V1\User;

use App\Domains\Workshop\Http\Resources\V1\WorkshopResource;
use App\Domains\Workshop\Repositories\Interfaces\WorkshopRepositoryInterface;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class GetWorkshopByIdFeature extends Feature
{

    public function handle(Request $request, WorkshopRepositoryInterface $workshop_repository)
    {

        $workshop  = $workshop_repository->getWorkshopById($request->id);
        if(!$workshop){
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'message' => 'this workshop is not exists',
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'workshop' => new WorkshopResource($workshop),
            ]
        ]);
    }
}
