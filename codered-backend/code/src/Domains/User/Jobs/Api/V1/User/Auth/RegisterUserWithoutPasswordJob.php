<?php

namespace App\Domains\User\Jobs\Api\V1\User\Auth;

use Illuminate\Http\Request;
use App\Domains\User\Enum\UserType;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserRepository;

class RegisterUserWithoutPasswordJob extends Job
{
    protected $request;

    protected $allowedInputs = [
        'first_name','last_name', 'password', 'email', 'image_id', 'social_id',
        'social_type', 'oauth2_client_id','country_id'
    ];

    /**
     * @var bool
     */
    private $generateToken;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     * @param bool    $generateToken
     */
    public function __construct(Request $request, bool $generateToken = true)
    {
        $this->request = $request;
        $this->generateToken = $generateToken;


    }

    /**
     * Execute the job.
     *
     * @param UserRepository $user_repository
     * @return User
     */
    public function handle(UserRepository $user_repository): User
    {

        $data = $this->request->only($this->allowedInputs);


        $data['country_id'] = $this->request->country_id;


        $data['type'] = UserType::USER;

        $user = $user_repository->fillAndSave($data);


        return $user;

    }

}
