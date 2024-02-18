<?php

namespace App\Domains\Uploads\Jobs;

use App\Domains\Uploads\Models\Upload;
use App\Foundation\Repositories\Repository;
use INTCore\OneARTFoundation\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class MarkFileAsUsedJob extends Job
{
    use Queueable;
    
    protected $file_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_id)
    {
        $this->file_id = $file_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : Bool
    {
        $repo = new Repository(new Upload);
        $upload = $repo->find($this->file_id);
        return $upload->update(['in_use' => Upload::IN_USE]);
    }


}
