<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\SubAccount;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class AssignLicensesToSubAccountJob extends Job
{
    private $premiumLicenses;
    private $trialLicenses;
    private $subAccount_id;
    private $admin;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($trialLicenses, $premiumLicenses, $subAccount_id, $admin)
    {
        $this->premiumLicenses = $premiumLicenses;
        $this->trialLicenses = $trialLicenses;
        $this->subAccount_id = $subAccount_id;
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // check if Enterprise has premium licenses
        $premiumLicenses = $this->admin->licenses()->where('user_id', null)->where('subaccount_id', null)->where('activation', '=', 1)
            ->where('license_type', LicneseType::PREMIUM)->orderBy('used_number')->get();
//        $trialLicenses = $this->admin->licenses()->where('user_id', null)->where('subaccount_id', null)->where('activation', '=', 1)
//            ->where('license_type', LicneseType::TRIAL)->orderBy('used_number')->get();
        for ($i = 0; $i < $this->premiumLicenses; $i++) {

            $premiumLicenses[$i]->subaccount_id = $this->subAccount_id;
            $premiumLicenses[$i]->save();
        }
//        for ($i = 0; $i < $this->trialLicenses; $i++) {
//            $trialLicenses[$i]->subaccount_id = $this->subAccount_id;
//            $trialLicenses[$i]->save();
//        }


    }
}
