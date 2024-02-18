<?php

namespace App\Domains\Voucher\Features\Api\V2\User\Profile;

use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Payments\Enum\PayableType;
use App\Domains\User\Models\Lookups\UserTag;
use App\Domains\User\Models\User;
use App\Domains\Voucher\Enum\VoucherUsageStatus;
use App\Domains\Voucher\Models\Voucher;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Log;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class UseVoucherFeature extends Feature
{
    public function handle(Request $request, Voucher $voucher)
    {
        $user = $request->user('api');

        $voucher = $voucher->where('voucher', $request->voucher)->first();
        if ($voucher && $voucher->isValid()) {
            // make user's subscription
            /** @var PackageSubscription $package */
            $package = $voucher->payable;
            if (empty($package)) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        "name"    => "voucher",
                        'message' => 'Invalid or expired voucher.'
                    ]
                ]);
            }

            $duration = now()->addDays($voucher->days);

            $subscription = $user->subscriptions()->create([
                'expired_at'      => $duration,
                'status'          => $voucher->access_type,
                'subscription_id' => $voucher->voucher,
                'package_id'      => $voucher->payable_id
            ]);


            $package = $voucher->payable;

            $course_id = json_decode($package->access_id)[0] ?? null;

            if ($package->course_type) {
                CourseEnrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course_id,
                    'expired_at' => $duration,
                    'user_subscription_id'  => $subscription->id->toString(),
                ]);
            }

            $voucher->update([
                'is_used' => VoucherUsageStatus::USED,
                'user_id' => $user->id
            ]);

            $this->syncUserTags($user, $voucher);

            $typeAccess = $voucher->payable_type == PayableType::SUBSCRIPTION ? 'Bundle / Pro Subscription' : 'Micro Degree';

            return $this->run(RespondWithJsonJob::class, [
                'content' => [
                    'status'  => true,
                    'message' => "Congratulations! You're now a premium EC-Council Learning member! Your premium access to {$typeAccess} expires at {$duration->format('Y-m-d')} ."
                ]
            ]);
        }

        return $this->run(RespondWithJsonErrorJob::class, [
            'errors' => [
                "name"    => "voucher",
                'message' => 'Invalid or expired voucher.'
            ]
        ]);
    }

    private function syncUserTags(User $user, Voucher $voucher)
    {

        if (empty($voucher->tags)) return;
        foreach ($voucher->tags as $key => $value) {
            $tag = UserTag::firstOrCreate(['name' => $value, 'activation' => 1]);

            try {
                $user->usertags()->syncWithoutDetaching($tag->id);
                $user->touch();
            } catch (\Exception $e) {

                Log::error("voucher claim error:" . $e->getMessage() . 'data:' . collect($voucher->tags)->toJson() . "voucher id: {$voucher->id}");
            }
        }
    }
}
