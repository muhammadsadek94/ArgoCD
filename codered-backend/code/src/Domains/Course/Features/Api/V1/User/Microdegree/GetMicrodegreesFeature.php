<?php

namespace App\Domains\Course\Features\Api\V1\User\Microdegree;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\MicrodegreeRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\Microdegree\MicrodegreeBasicInfoResource;


class GetMicrodegreesFeature extends Feature
{

    public function handle(Request $request, MicrodegreeRepositoryInterface $microdegree_repository)
    {
        $micro_degrees = $microdegree_repository->getMicrodegrees(4, ['lessons']);

        return $this->run(RespondWithJsonJob::class, [
            "content" => MicrodegreeBasicInfoResource::collection($micro_degrees)
        ]);
    }
}
