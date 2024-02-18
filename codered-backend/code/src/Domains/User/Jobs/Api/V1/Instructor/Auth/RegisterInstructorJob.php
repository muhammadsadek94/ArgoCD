<?php

namespace App\Domains\User\Jobs\Api\V1\Instructor\Auth;

use Illuminate\Http\Request;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Enum\UserActivation;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserRepository ;

class RegisterInstructorJob extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @param UserRepository $user_repository
     * @return User
     */
    public function handle(UserRepository $user_repository) :User
    {

        $user = $this->createUser($user_repository);

        $this->createInstructorProfile($user);


        return $user;

    }

    /**
     * @param UserRepository $user_repository
     * @return \Illuminate\Database\Eloquent\Model|\INTCore\OneARTFoundation\Model
     */
    private function createUser(UserRepository $user_repository)
    {
        $data = $this->request->only([
            "first_name", "phone", "email"
        ]);

        $data['type'] = UserType::PROVIDER;
        $data['activation'] = UserActivation::WAITING_APPROVAL;
        $user = $user_repository->fillAndSave($data);
        return $user;
    }

    private function createInstructorProfile(User $user)
    {
        $data = $this->request->only([
            'current_employer', 'designation',
            'linkedin_url', 'github_url', 'blog_url', 'article_url',
            'years_experience', 'profile_summary', 'cv_id',
            'have_courses', 'course_information', 'interested_video', 'interested_assessments',
            'interested_written_materials',
            'have_trending_course', 'trending_course_description', 'trending_course_topic',
            'trending_course_target_audience',
            'video_sample_id','job'
        ]);
        $data['user_id'] = $user->id;
        return $user->instructor_profile()->create($data);
    }

}
