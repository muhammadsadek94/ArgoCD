<?php

namespace App\Domains\ContactUs\Jobs\Api;

use App\Domains\ContactUs\Models\ContactUsSubject;
use INTCore\OneARTFoundation\Job;

class GetAllContactSubjectsJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rows = ContactUsSubject::active()->get();
        return $rows;
    }
}
