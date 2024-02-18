<?php

namespace App\Domains\ContactUs\Features\Api\V1;

use App\Domains\ContactUs\Jobs\Api\GetAllContactSubjectsJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class GetSubjectsFeature extends Feature
{
    public function handle(Request $request)
    {
        $subjects = $this->run(GetAllContactSubjectsJob::class);

        return $this->run(RespondWithJsonJob::class, [
           "content" =>  [
               "subjects" => $subjects
           ]
        ]);
    }
}
