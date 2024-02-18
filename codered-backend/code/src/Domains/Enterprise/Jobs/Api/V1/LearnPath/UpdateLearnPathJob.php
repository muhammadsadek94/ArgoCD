<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\LearnPath;


use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UpdateLearnPathJob extends Job
{
    protected $allowedInputs = [
        'name', 'amount', 'type', 'description', 'url', 'activation',
        'access_type', 'access_id', 'duration', 'deadline_type', 'expiration_date','expiration_days'
    ];

    public $request;
    public $packageSubscription;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $packageSubscription
     */
    public function __construct($request, $packageSubscription)
    {
        $this->request = $request;
        $this->packageSubscription = $packageSubscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->request->only($this->allowedInputs);

        $this->packageSubscription->update($data);
        $this->packageSubscription->save();

        return $this->packageSubscription;
    }
}
