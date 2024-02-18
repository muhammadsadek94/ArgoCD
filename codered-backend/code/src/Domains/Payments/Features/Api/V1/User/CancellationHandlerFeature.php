<?php

namespace App\Domains\Payments\Features\Api\V1\User;

use Illuminate\Http\Request;
use App\Domains\Course\Models\Course;
use INTCore\OneARTFoundation\Feature;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\Payments\Enum\PayableType;
use App\Domains\Payments\Models\PaymentTransactions;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Payments\Enum\PaymentTransactionStatus;
use App\Domains\Payments\Models\PaymentTransactionsHistory;
use App\Domains\Payments\Models\PaymentIntegration;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Course\Jobs\Api\V1\User\EnrollInCourseJob;
use App\Domains\Payments\Enum\SubscriptionPackageType;


class CancellationHandlerFeature extends Feature
{

    public function handle(Request $request)
    {

        $type = $request->type;
        $product = $request->product;
        $order = $request->order;


        $transaction = PaymentTransactions::where('order_id', $order['id'])->latest('created_at')->first();
        $payable = $transaction->payable;
        $user = $transaction->user;

        $this->updateTransactionStatus($transaction, $type);


        switch($transaction->payable_type) {
            case PayableType::SUBSCRIPTION:
                $this->revokeSubscription($user, $payable, $product['transaction_id'] ?? $order['subscription_id']);
                break;
            case PayableType::MICRODEGREE:
                $this->revokeMicrodegreeAccess($user, $payable, $product);
                break;
        }


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }



    private function revokeSubscription($user, PackageSubscription $package, $subscription_id)
    {
        // TODO: move to job
        $user->subscriptions()->where([
            'subscription_id' => $subscription_id,
        ])->delete();
    }

    private function revokeMicrodegreeAccess($user, Course $micro_degree, array $product)
    {
        //TODO: move to job
        $user->course_enrollments()->detach($micro_degree->id);
    }

    private function updateTransactionStatus(PaymentTransactions $transaction, $status)
    {

//        return $transaction->delete();
//
        $status = $this->translateStatus($status);
        return $transaction->update([
            'status' => $status
        ]);
    }

    private function translateStatus($status)
    {
        $localizations = [
            'Cancel' => PaymentTransactionStatus::CANCELLED,
            'Refund' => PaymentTransactionStatus::REFUNDED
        ];

        return $localizations[$status] ?? 0;
    }
}
