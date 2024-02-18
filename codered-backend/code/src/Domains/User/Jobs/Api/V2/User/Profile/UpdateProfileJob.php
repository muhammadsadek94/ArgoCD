<?php

namespace App\Domains\User\Jobs\Api\V2\User\Profile;


use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UpdateProfileJob extends Job
{
    protected $allowedInputs = ["first_name", "last_name", 'image_id', 'email', 'phone', 'country_id', 'city_id',
                                'gender', 'birth_date', 'is_profile_picture_updated'];

    public $request;
    public $user;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $user
     */
    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->request->only($this->allowedInputs);

        $this->user->update($data);

        return $this->user;
    }
}
