<?php

namespace App\Domains\User\Jobs\Api\V2\User\Profile;

use App\Domains\Uploads\Models\Upload;
use Illuminate\Http\Request;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UserDetailsJob extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() :User
    {
        $user = $this->request->user();
        if (empty($user->image_id)) {
            $user->image_id = Upload::where('is_default_profile_image',true)->inRandomOrder()->first()?->id;
            $user->update();
        }
        return $user;
    }
}
