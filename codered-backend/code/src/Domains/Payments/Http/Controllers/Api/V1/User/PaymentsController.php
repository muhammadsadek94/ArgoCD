<?php

namespace App\Domains\Payments\Http\Controllers\Api\V1\User;

use App\Domains\Payments\Features\Api\V1\User\CancellationHandlerFeature;
use App\Domains\Payments\Features\Api\V1\User\OrderCompleted;
use App\Domains\Payments\Features\Api\V1\User\StripPayment;
use App\Domains\Payments\Features\Api\V1\User\GetInvoicesFeature;
use App\Domains\Payments\Features\Api\V1\User\RequestCancelSubscriptionFeature;
use INTCore\OneARTFoundation\Http\Controller;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderCompleted()
    {
        return $this->serve(OrderCompleted::class);
    }

    public function orderCancelled()
    {
        return $this->serve(CancellationHandlerFeature::class);
    }

    public function postStripCharge()
    {
        return $this->serve(StripPayment::class);
    }

    public function getInvoices()
    {
        return $this->serve(GetInvoicesFeature::class);
    }

    public function postCancelSubscriptionRequest()
    {
        return $this->serve(RequestCancelSubscriptionFeature::class);
    }

}
