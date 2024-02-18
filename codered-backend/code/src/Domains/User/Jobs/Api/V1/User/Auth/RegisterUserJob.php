<?php

namespace App\Domains\User\Jobs\Api\V1\User\Auth;

use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserRepository;
use INTCore\OneARTFoundation\Job;

class RegisterUserJob extends Job
{
    protected $request;

    protected $allowedInputs = ['first_name', 'last_name', 'password', 'email', 'phone', 'social_id',
                                'social_type', 'birth_date', 'country_id'];

    /**
     * Create a new job instance.
     *
     * @param $request
     */
    public function __construct($request)
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

        $data = $this->request->only($this->allowedInputs);

        $data['type'] = UserType::USER;

        $data['activation'] = UserActivation::COMPLETE_PROFILE;

        $user = $user_repository->fillAndSave($data);

        // $user->setProfilePicture($this->request->profile_picture);

        $device_name = $this->request->get('device_name', 'Login via email & password');

        $user->access_token = $user->createToken($device_name);

        return $user;

    }

}