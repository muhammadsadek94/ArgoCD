<?php

namespace App\Domains\Enterprise\Features\Api\V1\User\Auth;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLearnPaths;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLearnPathsJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLicensesToUsersJob;
use App\Domains\Enterprise\Repositories\EnterpriseLearnPathRepository;
use App\Domains\Enterprise\Repositories\EnterpriseRepository;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\User\Enum\UserType;
use Constants;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\Auth\RegisterUserJob;
use App\Domains\User\Http\Resources\Api\V1\User\UserDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\User\Device\UpdateDeviceInfoJob;
use App\Domains\Enterprise\Http\Requests\Api\V1\User\RegisterUserRequest;
use App\Domains\Enterprise\Jobs\Api\V1\User\Activation\SendOtpActivationCodeJob;

class RegisterUserFeature extends Feature
{
    public function handle(RegisterUserRequest $request, EnterpriseRepository $enterpriseRepository, EnterpriseLearnPathRepository $enterpriseLearnPathRepository)
    {
        // register user in database
        $admin = auth()->user();
        $user = [];

        $licences = $enterpriseRepository->getLicenses($request, $admin);
        $licences_number = count($licences);
        $user = $this->run(RegisterUserJob::class, [
            'request' => $request,
        ]);
        if (!$user){
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "Licenses",
                    'message' => trans('enterprise::lang.can_not_convert_user')
                ]
            ]);
        }
        if ($request->has('learn_paths')) {
            if($licences_number < 1) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        "name" => "Licenses",
                        'message' => trans('enterprise::lang.no_licenses')
                    ]
                ]);
            }

            $has_pro_package = $enterpriseLearnPathRepository->checkForProPackage($request->learn_paths);

            if ( $has_pro_package){
                $this->run(AssignLicensesToUsersJob::class, [
                    'license' => $licences[0],
                    'user' => $user
                ]);

            }else{
                if($licences_number < count($request->learn_paths)){
                    return $this->run(RespondWithJsonErrorJob::class, [
                        'errors' => [
                            "name" => "Licenses",
                            'message' => trans('enterprise::lang.no_licenses')
                        ]
                    ]);
                }
                foreach ($request->learn_paths as $i => $path) {
                    $this->run(AssignLicensesToUsersJob::class, [
                        'license' => $licences[$i],
                        'user' => $user
                    ]);
                }
            }
            $this->run(AssignLearnPathsJob::class, [
                'learnPaths' => $request->learn_paths,
                'user' => $user
            ]);

            // check if package in request is pro assign 1 license

            // if no pro assign by the number of learn paths & check license count equal or greater than request learn paths
//            if ($licences_number > 0) {
//
//
//                $this->run(AssignLicensesToUsersJob::class, [
//                    'license' => $licences[0],
//                    'user' => $user
//                ]);
//                if (isset($request->learn_paths))
//                    $this->run(AssignLearnPathsJob::class, [
//                        'learnPaths' => $request->learn_paths,
//                        'user' => $user
//                    ]);
//            } else {
//                return $this->run(RespondWithJsonErrorJob::class, [
//                    'errors' => [
//                        "name" => "Licenses",
//                        'message' => trans('enterprise::lang.no_licenses')
//                    ]
//                ]);
//            }
        }


        // send otp
        $this->run(SendOtpActivationCodeJob::class, [
            "user" => $user,
        ]);

        // mark user profile image in use
        if ($request->has('image_id')) {
            $this->run(MarkFileAsUsedJob::class, [
                'file_id' => $request->image_id
            ]);
        }

        // update device token & language
        $this->run(UpdateDeviceInfoJob::class, [
            'token' => $user->access_token->token,
            'device_id' => $request->device_id ?? null,
            'language' => $request->get('language') ?? $request->header('Accept-Language') ?? Constants::DEFAULT_LANGUAGE
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => new UserDetailsResource($user)
        ]);
    }
}
