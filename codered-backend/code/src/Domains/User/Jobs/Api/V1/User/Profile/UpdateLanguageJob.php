<?php

namespace App\Domains\User\Jobs\Api\V1\User\Profile;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UpdateLanguageJob extends Job
{

    protected $language;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $language
     */
    public function __construct($user, $language)
    {
        $this->language = $language;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return User
     */
    public function handle() :User
    {
        $this->user->update(['language' => $this->language]);
        return $this->user;
    }
}
