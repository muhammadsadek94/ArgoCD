<?php

namespace App\Domains\Course\Features\Api\V1\User\Microdegree;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\MicrodegreeRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\Microdegree\MicrodegreeInformationResource;


class GetMicrodegreeByIdFeature extends Feature
{

    public function handle(Request $request, MicrodegreeRepositoryInterface $microdegree_repository)
    {

        $micro_degree = $microdegree_repository->getMicroDegreeById($request->course_id);

        return $this->run(RespondWithJsonJob::class, [
            "content" => new MicrodegreeInformationResource($micro_degree)
        ]);
    }
}
