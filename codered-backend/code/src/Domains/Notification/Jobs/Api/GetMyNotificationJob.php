<?php

namespace App\Domains\Notification\Jobs\Api;

use Illuminate\Support\Facades\Auth;
use INTCore\OneARTFoundation\Job;

class GetMyNotificationJob extends Job
{
    public $request;
    public $perPage;
    private $user;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $perPage
     * @param $user
     */
    public function __construct($request, $perPage, $user)
    {
        $this->request = $request;
        $this->perPage = $perPage;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->user->notifications()->latest()->paginate($this->perPage);
    }
}
