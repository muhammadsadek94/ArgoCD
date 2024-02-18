<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\SubAccount;

use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use INTCore\OneARTFoundation\Job;

class AssignLearnPathToSubAccountJob extends Job
{
    private $packages;
    private $subAccount_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($packages, $subAccount_id)
    {
        $this->subAccount_id = $subAccount_id;
        $this->packages = $packages;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->packages as $package) {

            $data = [
                'package_id' => $package,
//                'type' => $type,
                'enterprise_id' => $this->subAccount_id,
                'activation' => true
            ];
            EnterpriseLearnPath::firstOrCreate($data);
        }
    }
}
