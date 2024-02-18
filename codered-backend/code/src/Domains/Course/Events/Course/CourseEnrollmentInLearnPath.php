<?php

namespace App\Domains\Course\Events\Course;

use App\Domains\Course\Models\Course;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\User\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseEnrollmentInLearnPath
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Course
     */
    public $course;
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
     * @param Course $course
     * @param User   $user
     */
    public function __construct(Course $course, User $user, LearnPathInfo $learn_path)
    {
        $this->course = $course;
        $this->user = $user;
        $this->learn_path = $learn_path;
    }


}
