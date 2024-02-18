<?php

namespace App\Domains\Course\Events\Course;

use App\Domains\Course\Models\Chapter;
use App\Domains\User\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChapterCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Chapter
     */
    public $chapter;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param Chapter $chapter
     * @param User    $user
     */
    public function __construct(Chapter $chapter, User $user)
    {
        $this->chapter = $chapter;
        $this->user = $user;
    }


}
