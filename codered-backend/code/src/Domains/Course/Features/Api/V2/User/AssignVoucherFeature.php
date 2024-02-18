<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Http\Requests\Api\InteractWithLessonTaskRequest;
use App\Domains\Course\Jobs\Api\V1\User\AssignUserToVoucherJob;
use App\Domains\Course\Jobs\Api\V1\User\InteractWithLessonTaskJob;
use App\Foundation\Traits\Authenticated;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class AssignVoucherFeature extends Feature
{
    use Authenticated;

    public function handle(Request $request)
    {
        $voucher = $this->run(AssignUserToVoucherJob::class, [
            'user' => $request->user('api'),
            'lesson_id' => $request->lesson_id,
        ]);
        
        if ($voucher == null){
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "voucher",
                    'message' => 'No Voucher is exists '
                ]
            ]);
        }
        else {
            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'voucher' => $voucher
                ]
            ]);
        }

    }
}
