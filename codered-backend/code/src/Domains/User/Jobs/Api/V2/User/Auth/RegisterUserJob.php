<?php

namespace App\Domains\User\Jobs\Api\V2\User\Auth;

use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Enum\UserActivation;
use Illuminate\Http\Request;
use App\Domains\User\Enum\UserType;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserRepository;

class RegisterUserJob extends Job
{
    protected $request;

    protected $allowedInputs = [
        'first_name','last_name', 'password', 'email', 'image_id', 'social_id',
        'social_type', 'oauth2_client_id','country_id', 'utm_data'
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
        $this->merge_UTM_data();
        $data = $this->request->only($this->allowedInputs);

        $data['country_id'] = $this->request->country_id;

        $data['type'] = UserType::USER;
        if (empty($this->request->image_id)) {
            $data['image_id']= Upload::where('is_default_profile_image',true)->inRandomOrder()->first()?->id;
        }
        $data['activation'] = UserActivation::COMPLETE_PROFILE;

        $user = $user_repository->fillAndSave($data);

        if ($this->generateToken == true) {
            $device_name = $this->request->get('device_name', 'Login via email & password');
            $user->access_token = $user->createToken($device_name);
        }

        return $user;

    }


    /**
     * @param array|null $utm
     * @return void
     */
    private function merge_UTM_data(): void
    {
        if (!is_array($this->request->utm)) return;

        $utm_keys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];
        $data = [];
        foreach($this->request->utm as $key => $value){
            if (in_array($key, $utm_keys)){
                $data[$key] = $value;
            }
        }

        $this->request->merge(['utm_data' => json_encode($data)]);
    }
}
