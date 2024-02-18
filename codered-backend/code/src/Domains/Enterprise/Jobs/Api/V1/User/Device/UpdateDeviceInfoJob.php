<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\Device;

use INTCore\OneARTFoundation\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class UpdateDeviceInfoJob extends Job
{
    use Queueable;
    
    private $token;
    private $device_id;
    private $language;

    /**
     * SaveDeviceTokenJob constructor.
     * @param $token
     * @param $device_id
     * @param $language
     */
    public function __construct($token, $device_id, $language = 'en')
    {
        $this->token = $token;
        $this->device_id = $device_id;
        $this->language = $language;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle() :bool
    {
        return $this->token->update([
            'device_id' => $this->device_id,
            'language'  => $this->language
        ]);
    }
}
