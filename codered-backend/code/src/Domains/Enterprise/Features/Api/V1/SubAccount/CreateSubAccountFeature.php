<?php

namespace App\Domains\Enterprise\Features\Api\V1\SubAccount;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Http\Requests\Api\V1\SubAccount\CreateSubAccountRequest;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\SubAccount\AssignLearnPathToSubAccountJob;
use App\Domains\Enterprise\Jobs\Api\V1\SubAccount\AssignLicensesToSubAccountJob;
use App\Domains\Enterprise\Jobs\Api\V1\SubAccount\CreateSubAccountJob;
use App\Domains\User\Enum\UserType;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class CreateSubAccountFeature extends Feature
{


    public function handle(CreateSubAccountRequest $request)
    {


        $admin = auth()->user();
        //check if there is an learnpaths
        if ($admin->type == UserType::REGULAR_ENTERPRISE_ADMIN && (isset($request->learn_paths)&& count($request->learn_paths) == 0)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'learn path',
                    "message" => 'Learning Paths filed is required'
                ]
            ]);
        }
        // check if Enterprise has premium licenses
        $premiumLicenses = $admin->licenses->where('user_id', null)->where('subaccount_id', null)->where('activation', '=', 1)
            ->where('license_type', LicneseType::PREMIUM)->count();
        $trialLicenses = $admin->licenses->where('user_id', null)->where('subaccount_id', null)->where('activation', '=', 1)
            ->where('license_type', LicneseType::TRIAL)->count();

        if ($premiumLicenses < $request->premiumLicenses) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'premiumLicenses',
                    "message" => 'you don`t have a premium licenses'
                ]
            ]);
        }


        if ($trialLicenses < $request->trialLicenses) {

            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'trialLicenses',
                    "message" => 'you don`t have a trial licenses'
                ]
            ]);
        }

        $user = $this->run(CreateSubAccountJob::class, [
            'request' => $request
        ]);
        $user->refresh();
        $this->run(AssignLicensesToSubAccountJob::class, [
            'premiumLicenses' => $request->premiumLicenses,
            'trialLicenses' => $request->trialLicenses,
            "subAccount_id" => $user->id,
            "admin" => $admin,
        ]);
        if ((isset($request->learn_paths)&& count($request->learn_paths) > 0)) {
            $this->run(AssignLearnPathToSubAccountJob::class, [
                "packages" => $request->learn_paths,
                "subAccount_id" => $user->id
            ]);

        }
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'user' => $user
            ]
        ]);
    }
}
