<?php

namespace App\Domains\User\Features\Api\V1\Instructor;

use App\Domains\User\Http\Resources\Api\V1\Instructor\PayoutResource;
use App\Domains\User\Repositories\PayoutRepository;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class PayoutsFeature extends Feature
{
    public function handle(Request $request, PayoutRepository $payout_repository)
    {
        $user = $request->user('api');
        $total_royalties = number_format($payout_repository->getTotalPayouts($user->id));
        $total_royalties_quarter = number_format($payout_repository->getLastQuarterAmount($user->id));
        $total_mins_watched = number_format($this->getInstructorViewedMins($user->id));

        $pending_payouts = $payout_repository->getPendingPayouts($user->id);
        $history_payouts = $payout_repository->getHistoryPayouts($user->id);


        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'total_royalties' => $total_royalties,
                'total_royalties_quarter' => $total_royalties_quarter,
                'total_mins_watched' => $total_mins_watched,
                'pending_payouts' => PayoutResource::collection($pending_payouts),
                'history_payouts' => PayoutResource::collection($history_payouts),
            ]
        ]);

    }

    /**
     * get instructor watched mins
     * TODO: refactor into repository
     *
     * @param string $instructor_id
     * @return float|int
     */
    private function getInstructorViewedMins(string $instructor_id)
    {
        $all_views_instructors = \App\Domains\Course\Models\WatchHistoryTime::where([
            'subscription_type' => \App\Domains\User\Enum\SubscribeStatus::ACTIVE,
        ])->whereHas('course', function ($query) use ($instructor_id) {
            $query->where('user_id', $instructor_id);
        })->get();

        $total_watched_seconds_instructor = 0;
        foreach ($all_views_instructors as $view_lesson) {
            $total_watched_seconds_instructor += $view_lesson->watched_time;
        }
        $total_watched_mins_instructor = $total_watched_seconds_instructor / 60;
        return (int) $total_watched_mins_instructor;
    }
}
