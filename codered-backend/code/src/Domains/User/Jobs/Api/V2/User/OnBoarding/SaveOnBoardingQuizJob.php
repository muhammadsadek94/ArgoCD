<?php

namespace App\Domains\User\Jobs\Api\V2\User\OnBoarding;

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Http\Requests\Api\V2\User\OnBoardingQuizRequest;

class SaveOnBoardingQuizJob extends Job
{
    public $request;
    public $user;
    private $course_categories;
    private $course_tags;
    private $goals;
    private $level;

    /**
     * Create a new job instance.
     *
     * @param $course_categories
     * @param $course_tags
     * @param $goals
     * @param $level
     * @param User $user
     */
    public function __construct($course_categories, $course_tags, $goals, $level, User $user)
    {
        $this->user = $user;
        $this->course_categories = $course_categories;
        $this->course_tags = $course_tags;
        $this->goals = $goals;
        $this->level = $level;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle() :bool
    {
        $user = $this->user;

        $user->tags()->sync($this->course_tags);
        $user->categories()->sync($this->course_categories);
        $user->goals()->sync($this->goals);

        return $user->update([
            'level_experience' => $this->level,
            'activation'       => UserActivation::ACTIVE
        ]);

    }

}
