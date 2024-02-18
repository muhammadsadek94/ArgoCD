<?php

namespace App\Domains\Course\Jobs\Api\V1\User;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;
use App\Domains\Course\Models\Lesson;
use INTCore\OneARTFoundation\QueueableJob;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\Course\Models\WatchingHistory;

class AddToWatchHistoryJob extends QueueableJob
{
    /**
     * @var User
     */
    private $user;
    private $lesson;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param Lesson $lesson
     */
    public function __construct(User $user, Lesson $lesson)
    {
        $this->user = $user;
        $this->lesson = $lesson;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() :Void
    {
        WatchingHistory::create([
            'user_id' => $this->user->id,
            'lesson_id' => $this->lesson->id,
            'course_id' => $this->lesson->course_id,
        ]);
    }
}
