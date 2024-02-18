<?php

namespace App\Domains\Geography\Jobs;

use App\Domains\Geography\Models\Country;
use INTCore\OneARTFoundation\Job;

class AllNationalitiesJob extends Job
{

    public function handle()
    {
        $nationalities = Country::active()->latest()->get();

        return $nationalities;
    }
}
