<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\LearnPath;

use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Enum\LearnPathsDeadlineType;
use INTCore\OneARTFoundation\Job;

class CreatePackageJob extends Job
{
    private $request;
    private $admin;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($admin ,$request)
    {

        $this->admin = $admin;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        $data = [
            'name' => $this->request->name,
            'access_type' => AccessType::LEARN_PATH_CAREER,
            'enterprise_id' => $this->admin->id,
            'activation' => true,
            'deadline_type'=> LearnPathsDeadlineType::NO_DEADLINE,
            'type'=> SubscriptionPackageType::CUSTOM
//            'expiration_date'=> LearnPathsDeadlineType::STATIC,
        ];
        return PackageSubscription::create($data);
    }
}
