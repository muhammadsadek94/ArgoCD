<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use App\Domains\Course\Services\CyberQ\CyberQService;
use App\Domains\User\Http\Requests\UpdateDisplayNameRequest;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Traits\Authenticated;

class UpdateDisplayNameFeature extends Feature
{

    use Authenticated;
    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(UpdateDisplayNameRequest $request)
    {

        $user = $this->auth('api');


        if(!$user) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'Unauthorized',
                    "message" => 'request cannot be resolved'
                ]
            ]);
        }

        $cyberqService = new CyberQService();
        if (empty($user->display_name)) {
            $cyberToken = $cyberqService->authenticate($user,$request->display_name);
        }
        $cyberToken = $cyberqService->authenticate($user);
        if ($cyberToken->data['success']) {
            $token = $cyberToken->data["data"]['token'];
            $responce = $cyberqService->updateDisplayName($user, $request->display_name,$token);
            if($responce->data['success']&& isset($responce->data["data"]['success'])){
                $user->display_name = $request->display_name;
                $user->is_display_name_updated= true;
                if($request->image_id)
                {
                    $user->image_id =  $request->image_id;
                    $user->is_profile_picture_updated = true;
                }
                $user->update();

                return $this->run(RespondWithJsonJob::class, [
                    "content" => true
                ]);
            }else {
                return $this->run(RespondWithJsonErrorJob::class, [
                    "errors" => [
                        'name' => 'this display name is taken',
                        "message" => 'this display name is taken'
                    ]
                ]);
            }
        }


    }
}
