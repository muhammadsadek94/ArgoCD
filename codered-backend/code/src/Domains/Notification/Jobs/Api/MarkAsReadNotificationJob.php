<?php

namespace App\Domains\Notification\Jobs\Api;

use App\Domains\Notification\Models\Notification;
use Illuminate\Support\Facades\Auth;
use INTCore\OneARTFoundation\Job;

class MarkAsReadNotificationJob extends Job
{
    private $request;
    private $perPage;
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
        $notifications = $this->user->notifications;
        foreach ($notifications as $notification){
            $notification->markAsRead();
        }
    }
}
