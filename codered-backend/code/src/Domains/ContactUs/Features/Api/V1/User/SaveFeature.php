<?php

namespace App\Domains\ContactUs\Features\Api\V1\User;

use App\Domains\ContactUs\Enum\ContactUsAppType;
use App\Domains\ContactUs\Http\Requests\Api\SaveMessageRequest;
use App\Domains\ContactUs\Jobs\Api\SaveUserMessageJob;
use App\Domains\ContactUs\Models\ContactUs;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class SaveFeature extends Feature
{
    public function handle(SaveMessageRequest $request)
    {
        $contact = $this->run(SaveUserMessageJob::class, [
            "request" => $request,
            'app_type'=> ContactUsAppType::USER_APP
        ]);

        return $this->run(RespondWithJsonJob::class, [
           "content" =>  [
               "contact_us" => $contact,
           ]
        ]);
    }
}
