<?php

namespace App\Domains\Payments\Features\Api\V1\User;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Events\Course\CourseEnrollment;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\PaymentTypes;
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
use App\Domains\Course\Models\CourseEnrollment as ModelsCourseEnrollment;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Jobs\Api\V1\User\CreateInvoicePdfJob;
use Carbon\Carbon;
use Composer\Package\Package;
use Log;

/**
 * this class stand for samcart payment call back
 * Class OrderCompleted
 *
 * @package App\Domains\Payments\Features\Api\V1\User
 */
class OrderCompleted extends Feature
{
    private $subscription_id = null;

    public function handle(Request $request)
    {
        Log::info("OrderCompleted: start purchasing product id:" . $request->product['id']);
        Log::info("OrderCompleted: purchasing information: \n " . collect($request->all())->toJson());


        $user = User::whereEmail($request->customer['email'])->first();
        $isNewUser = false;
        if (is_null($user)) {
            $isPhoneNumberInUse = User::where('phone', $request->customer['phone_number'])->count() > 0;
            //TODO: move to job
            $user = User::firstOrCreate([
                'email' => $request->customer['email']
            ], [
                'first_name' => $request->customer['first_name'],
                'last_name'  => $request->customer['last_name'],
                'phone'      => !$isPhoneNumberInUse ? $request->customer['phone_number'] : null,
                'activation' => UserActivation::COMPLETE_PROFILE,
                'type'       => UserType::USER
            ])->fresh();
            $isNewUser = true;
        }
        $type = $request->type;

        switch ($type) {
            case 'Order':
            case 'RecurringPaymentSucceeded':
                $this->orderCompleted($request, $user);
                break;
            default:
                Log::warning("OrderCompleted: unhandled samcart event named {$type}");
        }

        if ($isNewUser) {
            $this->setPasswordForNewUser($user);
        }

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
        $product = $request->product;
        $order = $request->order;
        $payment_type = $request->payment_type;

        $product_ids = explode(',', $product['id']);
        $product_prices = explode(',', $product['price']);

        if (count($product_ids) > 1) {
            foreach ($product_ids as $key => $id) {

                $each_product = [
                    'id'    => $id,
                    'price' => $product_prices[$key]
                ];

                $this->subscribeOrEnroll($request, $user, $each_product, $order, $payment_type);
            }
        } else {
            $this->subscribeOrEnroll($request, $user, $product, $order, $payment_type);
        }
    }

    private function subscribeOrEnroll($request, $user, $product, $order, $payment_type = PaymentTypes::SAMCART)
    {
        $integration = PaymentIntegration::where('product_id', $product['id'])->first();

        if (is_null($integration)) {
            Log::warning("OrderCompleted: product_id`" . $product['id'] . "` Not found ");
        }

        switch ($integration->payable_type) {
            case PayableType::SUBSCRIPTION:
                $this->subscribePackage($user, $integration->payable, $product, $order, $payment_type);
                break;
            case PayableType::MICRODEGREE:
                $this->enrollInMicroDegree($user, $integration->payable, $product, $payment_type);
                break;
        }
        // dd($product, $request, $user, $integration);
        $this->createTransaction($product, $request, $user, $integration);
    }

    private function enrollIndividualCourse($package, User $user, $duration, $subscription = null)
    {
        $course_id = json_decode($package->access_id)[0];

        ModelsCourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $course_id,
            'expired_at' => $duration,
            'user_subscription_id'  => $subscription->id,
        ]);
    }

    private function enrollInMicroDegree($user, Course $micro_degree, array $product, $payment_type = PaymentTypes::SAMCART)
    {

        $this->run(EnrollInCourseJob::class, [
            'user'      => $user,
            'course_id' => $micro_degree->id,
            'expired_at_date' => now()->addYear()
        ]);
    }

    private function subscribePackage(User $user, PackageSubscription $package, array $product, array $order, $payment_type = PaymentTypes::SAMCART)
    {

        if ($package->type == SubscriptionPackageType::MONTHLY || $package->type == SubscriptionPackageType::ANNUAL) {
            $subscription_id = $order['subscription_id'] ?? $product['transaction_id'] ?? $order['id'];
        } else {
            $subscription_id = $order['subscription_id'] ?? $product['transaction_id'] ?? $order['id'] . rand(10000, 99999);
        }
        $subscription = $user->subscriptions()->where('subscription_id', $subscription_id)->first();

        Log::info("message ". $subscription_id);

        $paid_amount = (float) $product['price'];
        $status = SubscribeStatus::ACTIVE;
        if (
            in_array($package->access_type, [AccessType::PRO, AccessType::LEARN_PATH_SKILL, AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_CERTIFICATE, AccessType::COURSE_CATEGORY, AccessType::COURSES])  ||
            $package->course_type == CourseType::COURSE_CERTIFICATION || $package->course_type == CourseType::MICRODEGREE
            ) {
                $status = ((int)$paid_amount == 1 || (int)$paid_amount == 0) ? SubscribeStatus::TRIAL : SubscribeStatus::ACTIVE;
            }
            if ($package->free_trial_days > 0 ) {
                if (empty($subscription)) {
                    $status= SubscribeStatus::TRIAL;
                }elseif ((int)$paid_amount != 0 && $subscription->status != 2 && $package->type != AccessType::PRO ) {
                    $status= SubscribeStatus::ACTIVE;
                }elseif ($package->type == AccessType::PRO && $subscription->status != 2 && (int)$paid_amount < 1 ) {
                    $status= SubscribeStatus::ACTIVE;
                }
                // $status = empty($subscription) ? SubscribeStatus::TRIAL : SubscribeStatus::ACTIVE;
            }

            if ($status == SubscribeStatus::TRIAL) {
                $duration = now()->addDays($package->free_trial_days ?? 7);
            } else {
                $duration = $package->type == SubscriptionPackageType::CUSTOM ? now()->addDays($package->duration) : ($package->type == SubscriptionPackageType::MONTHLY ? now()->addMonth() : now()->addYear());
            }

            if ($package->is_installment) {
                $subscription = $user->subscriptions()->where('package_id', $package->id)->where('user_id', $user->id)->where('is_installment', 1)->first();
                if ($subscription) {
                    $subscription_id = $subscription->subscription_id;
                }
            }

            $paid_installment_count = 0;

            if ($package->is_installment && $status == SubscribeStatus::TRIAL) {
                $paid_installment_count = 0;
            }

            if ($package->is_installment && $status == SubscribeStatus::ACTIVE && (int)$paid_amount != 0 ) {
                $paid_installment_count = 1;
            }

            if (empty($subscription) || $payment_type == PaymentTypes::RAZORPAY) {
                Log::info("OrderCompleted: new subscription. user: {$user->email}, product_id:" . $product['id']);
                $subscription = $user->subscriptions()->create([
                    'expired_at'      => $duration,
                    'status'          => $status,
                    'subscription_id' => $subscription_id,
                    'package_id'      => $package->id,
                    'is_installment'  => $package->is_installment,
                    'paid_installment_count' => $paid_installment_count
                ]);



                if ($package->access_type == AccessType::INDIVIDUAL_COURSE) {
                    $this->enrollIndividualCourse($package, $user, $duration, $subscription);
                }
            } else {
                Log::info("OrderCompleted: renew subscription. user: {$user->email}, product_id:" . $product['id'] . ", subscription_id: {$subscription_id}");
                if ((int)$paid_amount > 1) {

                    $user->subscriptions()->where('subscription_id', $subscription_id)->update([
                        'status' =>  $status,
                        'expired_at' => $duration,
                        'paid_installment_count' => $package->is_installment ? $subscription->paid_installment_count + 1 : 0
                    ]);
                }

                // dd($subscription->status);
            if ($package->access_type == AccessType::PRO || $subscription->paid_installment_count == 0) {
                $user->subscriptions()->where('subscription_id', $subscription_id)->update([
                    'expired_at' => $duration,
                ]);
            }

            // $subscription->status = SubscribeStatus::ACTIVE;
            // $subscription->expired_at = $duration;
            // $subscription->save();
        }
        return $subscription;
    }

    private function createTransactionLog($product, Request $request, User $user, PaymentIntegration $integration)
    {
        $info = [
            'product'  => $product,
            'order'    => $request->order,
            'customer' => $request->customer
        ];

        $transaction_data = [
            'user_id'                => $user->id,
            'payment_integration_id' => $integration->id,
            'payable_id'             => $integration->payable->id,
            'payable_type'           => $integration->payable_type,
            'more_info'              => collect($info)->toJson(),
            'order_id'               => $info['order']['id'],
            'status'                 => PaymentTransactionStatus::ACTIVE,
            'amount'                 => $product['price']
        ];

        $transaction = PaymentTransactionsHistory::create($transaction_data);

        //        $this->run(CreateInvoicePdfJob::class, [
        //            'transactions' => $transaction
        //        ]);
    }

    private function createTransaction($product, Request $request, User $user, PaymentIntegration $integration)
    {
        $info = [
            'product'  => $product,
            'order'    => $request->order,
            'customer' => $request->customer
        ];

        $transaction_data = [
            'user_id'                => $user->id,
            'payment_integration_id' => $integration->id,
            'payable_id'             => $integration->payable->id,
            'payable_type'           => $integration->payable_type,
            'more_info'              => collect($info)->toJson(),
            'order_id'               => $info['order']['id'],
            'status'                 => PaymentTransactionStatus::ACTIVE,
            'amount'                 => $product['price'] ?? 0
        ];

        $transaction = PaymentTransactions::create($transaction_data);

        //        $this->run(CreateInvoicePdfJob::class, [
        //            'transactions' => $transaction
        //        ]);
    }

    private function setPasswordForNewUser(User $user)
    {

        $random_pass = \Str::random(20);
        $user->update([
            'password' => $random_pass
        ]);

        $subject = "Your EC-Council Learning Account Details";
        Mail::to($user->email)->send(new SendPasswordMail($subject, $random_pass, $user));
    }
}
