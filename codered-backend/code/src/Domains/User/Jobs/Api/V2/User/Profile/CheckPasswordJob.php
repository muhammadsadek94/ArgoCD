<?php

namespace App\Domains\User\Jobs\Api\V2\User\Profile;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;
use Hash;

class CheckPasswordJob extends Job
{
    protected $request;
    protected $user;
    public function __construct($request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    public function handle() {
        $hashedPassword = $this->user->password;
        return Hash::check($this->request->password, $hashedPassword);
    }
}
