<?php

namespace App\Domains\Geography\Jobs\Api;

use App\Domains\Geography\Models\Area;
use INTCore\OneARTFoundation\Job;

class GetAllAreasJob extends Job
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return Area::active()->orderBy('updated_at', 'DESC')->get();
    }
}
