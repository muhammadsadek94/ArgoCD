<?php

namespace App\Domains\Course\Jobs\Api\V2\User;

use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\ProjectApplication;
use INTCore\OneARTFoundation\Job;

class AddProjectApplicationJob extends Job
{
    private $user;
    private $request;
    /**
     * @var Lesson
     */
    private $lesson;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $request, Lesson $lesson)
    {
        $this->user = $user;
        $this->request = $request;
        $this->lesson = $lesson;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        
        return ProjectApplication::updateOrCreate(
            [
                'user_id' => $this->user->id,
                'lesson_id' => $this->request->lesson_id,
                'course_id' => $this->lesson->course_id,
            ],
            [
                'user_id' => $this->user->id,
                'lesson_id' => $this->request->lesson_id,
                'course_id' => $this->lesson->course_id,
                'url' => $this->request->url,
            ]);
    }
}
