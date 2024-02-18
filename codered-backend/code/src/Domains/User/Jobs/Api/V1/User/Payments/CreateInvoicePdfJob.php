<?php

namespace App\Domains\User\Jobs\Api\V1\User\Payments;

use INTCore\OneARTFoundation\Job;
use App\Domains\User\Models\PaymentTransactions;
use PDF;
use App\Domains\User\Models\PaymentTransactionsHistory;

class CreateInvoicePdfJob extends Job
{

    /**
     * @var PaymentTransactions
     */
    private $transactions;

    /**
     * Create a new job instance.
     *
     * @param PaymentTransactions $transactions
     */
    public function __construct(PaymentTransactions $transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : Bool
    {
        $transaction = $this->transactions;
        $user = $transaction->user;
        $payable = $transaction->payable;
        $data = compact('transaction', 'user', 'payable');
        $pdf = PDF::loadView('user::pdf.user.invoices.invoice', $data);
        $path = ('/user-invoices/' . uniqid() . '.pdf');
        $public_path = public_path($path);
        $save = $pdf->save($public_path);

        return $this->transactions->update(['pdf_url' => $path]);
    }

}
