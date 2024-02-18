<?php

namespace App\Domains\Payments\Features\Api\V1\User;

use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Mails\SendPasswordMail;
use App\Domains\User\Models\User;
use App\Domains\Course\Models\Course;
use Illuminate\Support\Facades\Mail;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\Payments\Enum\PayableType;
use App\Domains\Payments\Models\PaymentTransactions;
use App\Domains\Payments\Enum\PaymentTransactionStatus;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Payments\Models\PaymentTransactionsHistory;
use App\Domains\Payments\Models\PaymentIntegration;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Course\Jobs\Api\V1\User\EnrollInCourseJob;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Jobs\Api\V1\User\CreateInvoicePdfJob;

/**
 * this class stand for strip payment call back
 * Class OrderCompleted
 *
 * @package App\Domains\Payments\Features\Api\V1\User
 */
class StripPayment extends Feature
{

    public function handle(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        if (empty($user)) return;


        $this->orderCompleted($request, $user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param         $user
     */
    public function orderCompleted(Request $request, $user): void
    {
        $product_id = $request->product_id;
        $subscription_id = $request->subscription_id;

        $integration = PaymentIntegration::where('product_id', $product_id)->first();
        if (is_null($integration)) return;

        switch ($integration->payable_type) {
            case PayableType::SUBSCRIPTION:
                $this->subscribePackage($user, $integration->payable, $product_id, $subscription_id);
                break;
        }

    }


    private function subscribePackage(User $user, PackageSubscription $package, $product_id, $subscription_id)
    {
        $subscription = $user->subscriptions()->where('subscription_id', $subscription_id)->first();


        $duration = $package->type == SubscriptionPackageType::CUSTOM ? now()->addDays($package->duration) :
                ($package->type == SubscriptionPackageType::MONTHLY ? now()->addMonth() : now()->addYear());

        if (empty($subscription)) {
            $subscription = $user->subscriptions()->create([
                'expired_at'      => $duration,
                'status'          => SubscribeStatus::ACTIVE,
                'subscription_id' => $subscription_id,
                'package_id'      => $package->id
            ]);
        } else {
            $subscription->status = SubscribeStatus::ACTIVE;
            $subscription->expired_at = $duration;
            $subscription->save();
        }

        return $subscription;

    }



}
