<?php

namespace App\Domains\ContactUs\Jobs\Api;

use App\Domains\ContactUs\Models\ContactUs;
use App\Foundation\Repositories\Repository;
use INTCore\OneARTFoundation\Job;
use Auth;

class SaveUserMessageJob extends Job
{
    public $request;
    public $app_type;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $app_type
     */
    public function __construct($request, $app_type)
    {
        $this->request = $request;
        $this->app_type = $app_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : ContactUs
    {
        $repo = new Repository(new ContactUs);
        $data = $this->request->except(["status", "app_type"]);
        $data['app_type'] = $this->app_type;
        return $repo->fillAndSave($data);
    }
}
