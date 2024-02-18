<?php

namespace App\Domains\User\Jobs\Api\V1\User\Auth;

use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use INTCore\OneARTFoundation\Job;

class LoginUserJob extends Job
{
    protected $request;

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
     * @return void
     */
    public function handle() :?User
    {
        if($this->request->has('email')) {
            $auth = $this->attempt([
                'email'    => $this->request->email,
                'password' => $this->request->password
            ]);
        } else {
            $auth = $this->attempt([
                'phone'    => $this->request->phone,
                'password' => $this->request->password
            ]);
        }
        if($auth) {
            $auth = Auth::user();
            $device_name = $this->request->get('device_name', 'Login via email & password');
            $auth->access_token = $auth->createToken($device_name);
            return $auth;
        }
        return null;
    }

    private function attempt(array $array): bool
    {
        return Auth::attempt($array);
//        return User::where($array)->first();
    }
}
