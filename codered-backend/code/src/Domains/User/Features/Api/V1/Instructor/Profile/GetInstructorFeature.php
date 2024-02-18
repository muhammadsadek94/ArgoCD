<?php

namespace App\Domains\User\Features\Api\V1\Instructor\Profile;

use App\Domains\User\Enum\UserType;
use App\Domains\User\Http\Resources\Api\V1\Instructor\InstructorResource;
use App\Domains\User\Jobs\Api\V1\Instructor\Auth\LogoutUserJob;
use App\Domains\User\Models\User;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class GetInstructorFeature extends Feature
{
    public function handle(Request $request)
    {
        $instructor = User::where('type', UserType::PROVIDER)->find($request->id);

        if(!$instructor){
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'message' => 'Instructor not exists!',
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'instructor'        => New InstructorResource($instructor),
            ]
        ]);
    }
}
