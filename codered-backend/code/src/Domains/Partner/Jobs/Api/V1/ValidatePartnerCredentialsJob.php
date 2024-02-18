<?php

namespace App\Domains\Partner\Jobs\Api\V1;

use App\Domains\Partner\Models\Partner;
use Illuminate\Database\Eloquent\Model;
use INTCore\OneARTFoundation\Job;

class ValidatePartnerCredentialsJob extends Job
{
    /**
     * @var string|null
     */
    private $partner_name;
    /**
     * @var string|null
     */
    private $partner_secret;

    /**
     * Create a new job instance.
     *
     * @param string|null $partner_name
     * @param string|null $partner_secret
     */
    public function __construct(?string $partner_name, ?string $partner_secret)
    {
        $this->partner_name = $partner_name;
        $this->partner_secret = $partner_secret;
    }

    /**
     * Execute the job.
     *
     * @return ?Model|object
     */
    public function handle()
    {
        $partner = Partner::where('partner_name', $this->partner_name)->first();
        if ($partner && \Hash::check($this->partner_secret, $partner->partner_secret)){
            return $partner;
        }
        return NULL;
    }
}
