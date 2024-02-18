<?php

namespace App\Domains\Voucher\Features\Api\V1\User\Profile;

use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Course\Jobs\Api\V1\User\EnrollInCourseJob;
use App\Domains\Payments\Enum\PayableType;
use App\Domains\User\Enum\SubscribeStatus;
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
//            $duration = $package->type == SubscriptionPackageType::CUSTOM ? now()->addDays($package->duration) :
//                ($package->type == SubscriptionPackageType::MONTHLY ? now()->addMonth() : now()->addYear());
            $duration = now()->addDays($voucher->days);

         if($voucher->payable_type == PayableType::SUBSCRIPTION) {

            $user->subscriptions()->create([
                'expired_at'      => $duration,
                'status'          => $voucher->access_type,
                'subscription_id' => $voucher->voucher,
                'package_id'      => $voucher->payable_id
            ]);

        }else if($voucher->payable_type == PayableType::MICRODEGREE) {

            $this->run(EnrollInCourseJob::class, [
                'user'      => $user,
                'course_id' => $voucher->payable_id,
                'expired_at_date' => $duration
            ]);
        }


            $voucher->update([
                'is_used' => VoucherUsageStatus::USED,
                'user_id' => $user->id
            ]);

            $this->syncUserTags($user, $voucher);

            return $this->run(RespondWithJsonJob::class, [
                'content' => [
                    'status'  => true,
                    'message' => "Congratulations! You're now a premium EC-Council Learning member! Your premium access expires at {$duration->format('Y-m-d')} ."
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

        if(empty($voucher->tags)) return;
        foreach ($voucher->tags as $key => $value) {
            $tag = UserTag::firstOrCreate(['name' => $value, 'activation' => 1]);

            try {
                $user->usertags()->attach($tag->id);
            } catch (\Exception $e) {
                // sometime occur error with no reason
                Log::error("voucher claim error:". $e->getMessage(). 'data:'. collect($voucher->tags)->toJson() . "voucher id: {$voucher->id}");
            }
        }

    }
}
