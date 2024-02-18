<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use App\Domains\Course\Services\CyberQ\CyberQService;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Traits\Authenticated;


class CheckDisplayNameFeature extends Feature
{

    use Authenticated;

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Request $request)
    {

        $user = $this->auth('api');

        // $request->validate([
        //     "display_name" => ['required','unique:users,display_name'],
        // ]);

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
        $userOldName = $user->display_name;
        $already_taken = User::where('display_name',$request->display_name)->first();

        if ($cyberToken->data['success']) {
            $token = $cyberToken->data["data"]['token'];
            $responce = $cyberqService->updateDisplayName($user, $request->display_name,$token);
            if($responce->data['success']&& isset($responce->data["data"]['success'])&& !$already_taken){

                $responce = $cyberqService->updateDisplayName($user, $userOldName,$token);
                return $this->run(RespondWithJsonJob::class, [
                    "content" => true
                ]);
            }else {
                return $this->run(RespondWithJsonJob::class, [
                    "content" => false
                ]);
            }
        }
    }
}
