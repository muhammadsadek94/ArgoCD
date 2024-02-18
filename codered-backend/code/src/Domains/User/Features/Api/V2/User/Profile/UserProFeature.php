<?php
namespace App\Domains\User\Features\Api\V2\User\Profile;

use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Traits\Authenticated;
use INTCore\OneARTFoundation\Feature;

class UserProFeature extends Feature {
    use Authenticated;
    public function handle() {
        $user = $this->auth('api');

        return $this->run(new RespondWithJsonJob([
            "pro" => $user->pro_user ? $user->pro_user->count() > 0 : false
        ]));
    }
}
