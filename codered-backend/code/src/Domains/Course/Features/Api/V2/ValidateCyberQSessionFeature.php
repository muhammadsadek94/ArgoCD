<?php

namespace App\Domains\Course\Features\Api\V2;

use App\Domains\Course\Services\CyberQ\CyberQService;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class ValidateCyberQSessionFeature extends Feature
{


    public function handle(Request $request)
    {

        $cyberqService = new CyberQService();

        $validation = $cyberqService->validateToken($request->access_token);


        if(empty($validation)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'message',
                    "message" => 'request cannot be resolved'
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => $validation
        ]);
    }
}
