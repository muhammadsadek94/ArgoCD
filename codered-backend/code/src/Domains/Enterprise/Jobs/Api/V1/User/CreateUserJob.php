<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\User;
use App\Domains\User\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Enum\UserType;
use Illuminate\Support\Facades\Auth;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Repositories\UserRepository;

class CreateUserJob extends Job
{
    protected $allowedInputs = [
        'first_name','last_name', 'password', 'email', 'image_id', 'social_id',
        'social_type', 'oauth2_client_id','country_id','sub_account_id'
    ];
    /**
     * Create a new job instance.
     *@param UserRepository $user_repository
     * @return User
     */
    public function __construct(Request $request, bool $generateToken = true)
    {
        $this->request = $request;
        $this->generateToken = $generateToken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserRepository $user_repository)
    {
        $data = $this->request->only($this->allowedInputs);
        $data['type'] = UserType::USER;
        $admin = auth()->user();

            if($admin->type == UserType::PRO_ENTERPRISE_ADMIN || $admin->type == UserType::REGULAR_ENTERPRISE_ADMIN){
                $data['enterprise_id'] = $admin->id;
            }

            if($admin->type == UserType::PRO_ENTERPRISE_SUBACCOUNT || $admin->type == UserType::REGULAR_ENTERPRISE_SUBACCOUNT){
                if(!($this->request->sub_account_id)){
                    $data['subaccount_id'] = $admin->id;
                    $data['enterprise_id'] = $admin->enterprise_id;
                }
                $data['subaccount_id'] = $this->request->sub_account_id;
                $data['enterprise_id'] = $admin->enterprise_id;
            }

            $user = $user_repository->fillAndSave($data);

            if($this->request->tags){
                $user->userTags()->sync($this->request->tags);
            }



            if ($this->generateToken == true)
            {
                $device_name = $this->request->get('device_name', 'Login via email & password');
                $user->access_token = $user->createToken($device_name);
            }
            return $user;


    }

}
