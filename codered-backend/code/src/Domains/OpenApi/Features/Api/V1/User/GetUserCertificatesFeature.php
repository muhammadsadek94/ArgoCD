<?php

namespace App\Domains\OpenApi\Features\Api\V1\User;


use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\OpenApi\Http\Resources\Api\V1\User\UserCertificateResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;


class GetUserCertificatesFeature extends Feature
{

    public function handle(Request $request, UserRepository $user_repository)
    {
        $user = $request->user('api');

        $completed_courses = $user_repository->getCompletedCourses($user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'user_certificate' => UserCertificateResource::collection($completed_courses)
            ]
        ]);
    }
}
