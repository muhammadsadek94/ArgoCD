<?php

namespace App\Domains\User\Jobs\Api\V1\Instructor\Profile;


use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UpdateProfileJob extends Job
{
    protected $allowedInputs = [
        "first_name", "last_name", 'image_id', 'email'];

    public $request;
    public $user;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $user
     */
    public function __construct($request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return User
     */
    public function handle(): User
    {
        $this->updateUserModel();
        return $this->user;
    }

    private function updateUserModel(): bool
    {
        $data = $this->request->only($this->allowedInputs);

        return $this->user->update($data);
    }
}
