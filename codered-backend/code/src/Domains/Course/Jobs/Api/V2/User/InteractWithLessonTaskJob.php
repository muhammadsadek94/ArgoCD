<?php

namespace App\Domains\Course\Jobs\Api\V2\User;

use App\Domains\Course\Models\LessonTask;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class InteractWithLessonTaskJob extends Job
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var bool
     */
    private $is_checked;
    /**
     * @var string
     */
    private $lesson_id;
    /**
     * @var string
     */
    private $lesson_task_id;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param bool $is_checked
     * @param string $lesson_id
     * @param string $lesson_task_id
     */
    public function __construct(User $user, bool $is_checked, string $lesson_id, string $lesson_task_id)
    {
        $this->user = $user;
        $this->is_checked = $is_checked;
        $this->lesson_id = $lesson_id;
        $this->lesson_task_id = $lesson_task_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->is_checked){
            $this->user->lesson_tasks()->attach($this->lesson_task_id, [
               'lesson_id' => $this->lesson_id,
            ]);
        }else{
            $this->user->lesson_tasks()->detach($this->lesson_task_id);
        }
    }
}
