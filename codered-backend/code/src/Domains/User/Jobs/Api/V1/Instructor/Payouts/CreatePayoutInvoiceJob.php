<?php

namespace App\Domains\User\Jobs\Api\V1\Instructor\Payouts;

use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Models\Instructor\Payout;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Models\PaymentTransactions;
use PDF;

class CreatePayoutInvoiceJob extends Job
{

    /**
     * @var PaymentTransactions
     */
    private $payout;

    /**
     * Create a new job instance.
     *
     * @param Payout $payout
     */
    public function __construct(Payout $payout)
    {
        $this->payout = $payout;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : Bool
    {
        $payout = $this->payout;
        $user = $payout->instructor;

        $data = compact('payout', 'user');
        $pdf = PDF::loadView('user::pdf.instructor.invoices.invoice', $data);
        $path = ('/instructor-invoices/' . uniqid() . '.pdf');
        $public_path = public_path($path);
        $save = $pdf->save($public_path);

        $file_id = $this->saveToFileManagerDB($path)->id;
        return $this->payout->update(['attachment_id' => $file_id]);
    }

    private function saveToFileManagerDB(string $path): Upload
    {
        return Upload::create([
            "path" => $path,
            "size" => 'system-generated',
            "mime_type" => 'application/pdf',
            'in_use' => 1,
        ]);
    }

}
