<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\profile;

use INTCore\OneARTFoundation\QueueableJob;

class UpdateUploadStatusJob extends QueueableJob
{
    private $user;
    private $upload_status;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$upload_status)
    {
        $this->user = $user;
        $this->upload_status = $upload_status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->upload_status = $this->upload_status;
        $this->user->save();
    }
}
