<?php

namespace App\Domains\Course\Events\Course;

use App\Domains\Course\Models\Course;
use App\Domains\User\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CertificateGenerated
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
     * @var string
     */
    public $url;

    /**
     * Create a new event instance.
     *
     * @param Course $course
     * @param User   $user
     */
    public function __construct(Course $course, User $user, string $url)
    {
        $this->course = $course;
        $this->user = $user;
        $this->url = $url;
    }


}
