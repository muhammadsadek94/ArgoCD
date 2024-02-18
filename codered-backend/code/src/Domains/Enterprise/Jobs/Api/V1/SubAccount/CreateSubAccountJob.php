<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\SubAccount;

use App\Domains\User\Enum\UserType;
use App\Domains\Enterprise\Mails\SendPasswordMail;
use App\Domains\User\Repositories\UserRepository;
use Exception;
use INTCore\OneARTFoundation\Job;

class CreateSubAccountJob extends Job
{
    private $request;
    protected $allowedInputs = [
        'first_name', 'last_name', 'company_name', 'password', 'email', 'image_id', 'social_id',
        'social_type', 'oauth2_client_id', 'country_id', 'sub_account_id'
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     */
    public function handle(UserRepository $user_repository)
    {
        $data = $this->request->only($this->allowedInputs);
        $data['type'] = UserType::USER;
        $admin = auth()->user();

        if ($admin->type == UserType::PRO_ENTERPRISE_ADMIN || $admin->type == UserType::REGULAR_ENTERPRISE_ADMIN) {
            $data['enterprise_id'] = $admin->id;
        }
        $data['type'] = $admin->type == UserType::PRO_ENTERPRISE_ADMIN ? UserType::PRO_ENTERPRISE_SUBACCOUNT : UserType::REGULAR_ENTERPRISE_SUBACCOUNT;
        $data ['activation'] = 1;

        $password = \Str::random(20);
        $data['password'] = $password;
        $user = $user_repository->fillAndSave($data);
        $subject = "Your EC-Council Learning Account Details";
        $head_message = $admin->first_name . ' have created a new EC-Council Learning sub Account .';

        try {
            \Mail::to($this->request->email)->send(new SendPasswordMail($subject, $password, $user, $head_message));
        } catch (Exception $e) {
        }

        return $user;


    }

}
