<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\SubAccount;

use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use INTCore\OneARTFoundation\Job;

class DeleteSubAccountLearnPathJob extends Job
{
    private $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       EnterpriseLearnPath::where('id', $this->request->id)->delete();
    }
}
