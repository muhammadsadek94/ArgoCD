<?php

namespace App\Domains\Course\Jobs\Api\V1\User;

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use INTCore\OneARTFoundation\Job;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseReview;

class SaveCourseReviewJob extends Job
{

    public  $user;
    private $name;
    private $rate;
    private $user_goals;
    private $recommendation;
    private $course_id;


    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $name
     * @param $rate
     * @param $user_goals
     * @param $recommendation
     * @param $course_id
     */
    public function __construct(User $user, $course_id,$name, $rate,$user_goals,$recommendation)
    {
        $this->user = $user;
        $this->name = $name;
        $this->rate = $rate;
        $this->user_goals = $user_goals;
        $this->recommendation = $recommendation;
        $this->course_id = $course_id;
    }

   /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : Void
    {
        CourseReview::create([
            'user_id'          => $this->user->id,
            'name'             => $this->name,
            'rate'             => $this->rate,
            'user_goals'       => $this->user_goals,
            'recommendation'   => $this->recommendation,
            'course_id'        => $this->course_id,
        ]);
    }
}
