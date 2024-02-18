<?php

namespace App\Domains\User\Features\Api\V1\User\Profile\Payments;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\User\Repositories\UserRepository;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Http\Resources\Api\V1\User\TransactionResource;

class GetInvoicesFeature extends Feature
{

    public function handle(Request $request, UserRepository $user_repository)
    {
        $user = $request->user('api');
        $transactions = $user_repository->getTransactionsHistory($user);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'transactions' => TransactionResource::collection($transactions)
            ]
        ]);
    }
}
