<?php

namespace App\Domains\Challenge\Features\Api\V2;

use App\Domains\Challenge\Http\Requests\Api\CompetitionCompletedRequest;
use App\Domains\Challenge\Jobs\Api\V2\CompetitionCompletedJob;
use App\Domains\Challenge\Jobs\Api\V2\GetUserOrCreateGuestJob;
use App\Domains\Challenge\Models\Challenge;
use INTCore\OneARTFoundation\Feature;


class CompetitionCompletedFeature extends Feature
{
    public function handle(CompetitionCompletedRequest $request)
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
            'last_name' => $request->LastName
        ]);

        $competition_completed = $this->run(CompetitionCompletedJob::class, [
            'request' => $request,
            'data' => $data
        ]);

        return response()->json([
            'success' => $competition_completed,
        ]);
    }

}
