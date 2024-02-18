<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\LearnPath;

use App\Domains\Enterprise\Http\Requests\Api\V1\LearnPath\CreateLearnPathRequest;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use INTCore\OneARTFoundation\Job;

class CreateLearnPathJob extends Job
{
    private $admin;
    private $package;
    /**
     * Create a new job instance.
     * @param $admin
     * @param $package
     * @return void
     */
    public function __construct($admin ,$package)
    {

        $this->admin = $admin;
        $this->package = $package;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        $data = [
            'package_id' => $this->package->id,
            'enterprise_id' => $this->admin->id,
            'activation' => true

        ];
        return EnterpriseLearnPath::create($data);
    }
}
