<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;

use App\Domains\Course\Http\Resources\Api\V1\Microdegree\MicrodegreeBasicInfoResource;
use App\Domains\Course\Repositories\CertificationRepositoryInterface;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;

class MyCertificationsFeature extends Feature
{

    public function handle(Request $request, CertificationRepositoryInterface $certification_repository)
    {
        $user = $request->user('api');
        $not_completed_certifications = $certification_repository->getNotCompletedCertifications($user);
        $completed_certifications = $certification_repository->getCompletedCertifications($user);
        
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'certifications'                  => MicrodegreeBasicInfoResource::collection($not_completed_certifications),
                'completed_certifications'        => MicrodegreeBasicInfoResource::collection($completed_certifications),
            ]
        ]);
    }
}
