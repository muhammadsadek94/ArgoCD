<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;

use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\LearnPathCertificate;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\UserCertificateLearningPathResource;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\UserCertificateResource;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;

class GetUserCertificateByIdFeature extends Feature
{
    public function handle(Request $request, CompletedCourses $completed_courses, LearnPathCertificate $learn_path_certificate)
    {
        $user = $request->user('api');
        $certifcate = $completed_courses->find($request->id);


        if(empty($certifcate)) {
            $certifcate = $learn_path_certificate->find($request->id);

            if(!$certifcate){
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        'message' => 'Certificate not exists!',
                    ]
                ]);
            }

            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'user_certificate' => new UserCertificateLearningPathResource($certifcate)
                ]
            ]);
        }

        if(!$certifcate){
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'message' => 'Certificate not exists!',
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'user_certificate' => new UserCertificateResource($certifcate)
            ]
        ]);
    }
}
