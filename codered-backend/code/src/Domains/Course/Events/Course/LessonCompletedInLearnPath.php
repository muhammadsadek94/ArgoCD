<?php

namespace App\Domains\Course\Events\Course;

use App\Domains\Course\Models\Lesson;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\User\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessonCompletedInLearnPath
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
     * @var LearnPathInfo
     */
    public $learn_path;

    /**
     * Create a new event instance.
     *
     * @param Lesson $lesson
     * @param User   $user
     */
    public function __construct(Lesson $lesson, User $user, LearnPathInfo $learn_path)
    {
        $this->lesson = $lesson;
        $this->user = $user;
        $this->learn_path = $learn_path;
    }

}
