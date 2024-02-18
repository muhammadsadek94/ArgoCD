<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;


use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\UserCertificateResource;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\UserLearnPathCertificateResource;

class GetUserCertificatesFeature extends Feature
{

    public function handle(Request $request, UserRepository $user_repository)
    {
        $user = $request->user('api');

        $completed_courses = $user_repository->getCompletedCourses($user);

        $completed_learn_paths = $user_repository->getCompletedLearnPaths($user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'user_certificate'          => UserCertificateResource::collection($completed_courses),
                'learn_paths_certificates'  => UserLearnPathCertificateResource::collection($completed_learn_paths),
            ]
        ]);
    }
}
