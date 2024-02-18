<?php

namespace App\Domains\Course\Events\Course;

use App\Domains\Course\Models\Lesson;
use App\Domains\User\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessonCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Lesson
     */
    public $lesson;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param Lesson $lesson
     * @param User   $user
     */
    public function __construct(Lesson $lesson, User $user)
    {
        $this->lesson = $lesson;
        $this->user = $user;
    }

}
