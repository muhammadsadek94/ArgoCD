<?php

namespace App\Domains\Challenge\Features\Api\V2;


use App\Domains\Challenge\Http\Requests\Api\FlagSubmissionRequest;
use App\Domains\Challenge\Jobs\Api\V2\GetUserOrCreateGuestJob;
use App\Domains\Challenge\Jobs\Api\V2\SubmitFlagJob;
use App\Domains\Challenge\Models\Challenge;
use INTCore\OneARTFoundation\Feature;


class FlagSubmissionFeature extends Feature
{
    public function handle(FlagSubmissionRequest $request)
    {

        if (!Challenge::where('competition_id', $request->CompetitionId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Competition not found'
            ], 422);
        }

        $data = $this->run(GetUserOrCreateGuestJob::class, [
            'email' => $request->UserId,
            'first_name' => $request->FirstName,
            'last_name' => $request->LastName,
            'display_name' => $request->DisplayName
        ]);

        $flag_submission = $this->run(SubmitFlagJob::class, [
            'request' => $request,
            'data' => $data
        ]);

        return response()->json([
            'success' => $flag_submission,
        ]);
    }
}
