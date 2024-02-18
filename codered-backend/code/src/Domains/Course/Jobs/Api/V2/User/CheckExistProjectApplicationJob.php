<?php

namespace App\Domains\Course\Jobs\Api\V2\User;

use App\Domains\Course\Models\ProjectApplication;
use INTCore\OneARTFoundation\Job;

class CheckExistProjectApplicationJob extends Job
{
    private $user;
    private $lesson;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $lesson)
    {
        $this->user = $user;
        $this->lesson = $lesson;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        return ProjectApplication::where([
                'user_id' => $this->user->id,
                'lesson_id' => $this->lesson->id,
                'course_id' => $this->lesson->course_id,
            ])
            ->first();
    }
}
