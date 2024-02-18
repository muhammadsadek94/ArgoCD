<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\Enterprise;

use App\Domains\Enterprise\Enum\LicneseType;
use INTCore\OneARTFoundation\Job;

class CheckEnterpriseAvailableLicenseJob extends Job
{
    private $request;
    private $license_type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $license_type)
    {
        $this->request = $request;
        $this->license_type = $license_type;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        $admin = auth()->user();
        // check for  PREMIUM
        if ($this->license_type == LicneseType::PREMIUM) {
            $premiumLicenses = $admin->licenses->where('user_id', null)->where('subaccount_id', null)->where('activation', '=', 1)
                ->where('license_type', LicneseType::PREMIUM)->count();

            if ($premiumLicenses < $this->request->premiumLicenses) {
                return true;
            } else return false;
        }

        // check for  TRIAL
        if ($this->license_type == LicneseType::TRIAL) {
            $trialLicenses = $admin->licenses->where('user_id', null)->where('subaccount_id', null)->where('activation', '=', 1)
                ->where('license_type', LicneseType::TRIAL)->count();

            if ($trialLicenses < $this->request->trialLicenses) {
                return true;
            } else return false;
        }

    }
}
