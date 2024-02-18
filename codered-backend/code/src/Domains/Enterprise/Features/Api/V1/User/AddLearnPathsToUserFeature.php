<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\User\GetUserByidJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLearnPathsJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLicensesToUsersJob;
use App\Domains\Enterprise\Repositories\EnterpriseLearnPathRepository;
use App\Domains\Enterprise\Repositories\EnterpriseRepository;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\User;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use INTCore\OneARTFoundation\Feature;

class AddLearnPathsToUserFeature extends Feature
{

    public function handle(Request $request, EnterpriseLearnPathRepository $enterpriseLearnPathRepository, EnterpriseRepository $enterpriseRepository)
    {
        $admin = auth()->user();
//        $licences = $admin->licenses()->where('user_id', null)->get();
        $user = User::where('id', $request->user_id)
            ->where(function ($query) use ($admin) {
                $query->where('enterprise_id', $admin->id)
                    ->orwhere('subaccount_id', $admin->id);
            })->first();
        if (!$user) {
           return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'user',
                    'message' => 'No User Found'
                ]
            ]);
        }
        if ($user->activation == UserActivation::SUSPEND) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'user',
                    'message' => 'Please activate the user to start assigning learning paths'
                ]
            ]);
        }
        $licences = $enterpriseRepository->getLicenses($request, $admin);
        $licences_number = count($licences);
        if($licences_number < 1) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "Licenses",
                    'message' => trans('enterprise::lang.no_licenses')
                ]
            ]);
        }

        $has_pro_package = $enterpriseLearnPathRepository->checkForProPackage($request->learn_paths);
        if (!$user->hasActiveSubscription()) {
            if ($has_pro_package) {
                $this->run(AssignLicensesToUsersJob::class, [
                    'license' => $licences[0],
                    'user' => $user
                ]);

            } else {
                if ($licences_number < count($request->learn_paths)) {
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
        }
            $this->run(AssignLearnPathsJob::class, [
                'learnPaths' => $request->learn_paths,
                'user' => $user
            ]);


            $updateUser = User::where('id',$request->user_id)->first();

            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'user' => new EnterpriseUserDetailsResource($updateUser)
                ]
            ]);
    }
}
