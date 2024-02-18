<?php

namespace App\Domains\Enterprise\Features\Api\V1\SubAccount;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Http\Requests\Api\V1\SubAccount\AddNewSubAccountLiceneseRequest;
use App\Domains\Enterprise\Jobs\Api\V1\Enterprise\CheckEnterpriseAvailableLicenseJob;
use App\Domains\Enterprise\Jobs\Api\V1\SubAccount\AssignLicensesToSubAccountJob;
use App\Domains\User\Enum\UserType;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class AddNewSubAccountLicneseFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(AddNewSubAccountLiceneseRequest $request)
    {

        $admin = auth()->user();
        // check if Enterprise has premium licenses
        $checkPremium = $this->run(CheckEnterpriseAvailableLicenseJob::class, [
            'request' => $request,
            'license_type' => LicneseType::PREMIUM
        ]);
        if ($checkPremium) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'premiumLicenses',
                    "message" => 'you don`t have a premium licenses'
                ]
            ]);
        }

        $checkTrial = $this->run(CheckEnterpriseAvailableLicenseJob::class, [
            'request' => $request,
            'license_type' => LicneseType::TRIAL
        ]);

        if ($checkTrial) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'trialLicenses',
                    "message" => 'you don`t have a trial licenses'
                ]
            ]);
        }

        $this->run(AssignLicensesToSubAccountJob::class, [
            'premiumLicenses' => $request->premiumLicenses,
            'trialLicenses' => $request->trialLicenses,
            "subAccount_id" => $request->subAccount_id,
            "admin" => $admin,
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => 'success'
        ]);
    }
}
