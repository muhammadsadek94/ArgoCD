<?php

namespace App\Domains\Challenge\Features\Api\V2;

use App\Domains\Challenge\Repositories\ChallengeRepository;
use App\Domains\Course\Http\Resources\Api\V2\Challenge\ChallengeFullInfoResource;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;


class GetChallengeByIdFeature extends Feature
{
    public function handle(Request $request, ChallengeRepository $challengeRepository)
    {
        $user = $request->user('api');

        $challenge = $challengeRepository->getChallengeBySlug($request->slug);

        $allowed = $user->certifications_enrollments->where('challenge_id', $challenge->id)->where('pivot.expired_at', '>', Carbon::now())?->count();

        if (!$allowed) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'message' => 'Challenge not found',
                    'status' => 422
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'challenge' => new ChallengeFullInfoResource($challenge)
            ]
        ]);
    }
}
