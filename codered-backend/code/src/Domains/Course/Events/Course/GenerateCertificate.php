<?php

namespace App\Domains\Course\Events\Course;

use App\Domains\Course\Models\Course;
use App\Domains\User\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GenerateCertificate
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
     * Create a new event instance.
     *
     * @param Course $course
     * @param User   $user
     */
    public function __construct(Course $course, User $user)
    {
        $this->course = $course;
        $this->user = $user;
    }


}
