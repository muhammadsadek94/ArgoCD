<?php

namespace App\Domains\User\Jobs\Api\V1\Instructor\Profile;


use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UpdateBankInfoJob extends Job
{
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
        $this->updateInstructorProfile();
        return $this->user;
    }

    public function updateInstructorProfile(): bool
    {
        return $this->user->instructor_profile->update($this->request->only([
            'bank_name',
            //            'account_number',
            'iban',
            'swift_code',
            'billing_address',
            'payee_name',
            'payee_bank_country',
            'payee_branch_name',
            'branch_code',
            'intermediary_bank',
            'routing_number',
            'payee_bank_for_tt',
        ]));
    }
}
