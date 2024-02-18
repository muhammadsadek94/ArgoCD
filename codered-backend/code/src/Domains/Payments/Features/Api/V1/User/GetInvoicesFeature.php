<?php

namespace App\Domains\Payments\Features\Api\V1\User;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\Payments\Repositories\PaymentTransactionsRepository;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Payments\Http\Resources\Api\V1\User\TransactionResource;

class GetInvoicesFeature extends Feature
{

    public function handle(Request $request, PaymentTransactionsRepository $paymenttransaction_repository)
    {
        $user = $request->user('api');
        $transactions = $paymenttransaction_repository->getTransactionsHistory($user);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'transactions' => TransactionResource::collection($transactions)
            ]
        ]);
    }
}
