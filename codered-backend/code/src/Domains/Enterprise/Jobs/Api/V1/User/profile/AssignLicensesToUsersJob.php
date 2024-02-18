<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\profile;

use App\Domains\Enterprise\Enum\LicneseStatusType;
use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use App\Domains\Enterprise\Models\License;
use App\Domains\Enterprise\Repositories\EnterpriseRepository;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\User;
use App\Domains\User\Models\UserSubscription;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class AssignLicensesToUsersJob extends Job
{
    use Queueable;

    private $license;
    private $user;
    private $subscribtionStatus;

    /**
     * Create a new job instance.
     *
     * @param $license
     * @param $user
     */
    public function __construct($user, $license)
    {
        $this->user = $user;
        $this->license = $license;
        // decide the type of subscribtions
        if ($this->license->license_type == LicneseType::PREMIUM) {
            $this->subscribtionStatus = SubscribeStatus::ACTIVE;

        } else {
            $this->subscribtionStatus = SubscribeStatus::TRIAL;

        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EnterpriseRepository $enterpriseRepository)
    {

        $enterpriseRepository->assignLicensesToUser($this->user , $this->license);

    }
}
