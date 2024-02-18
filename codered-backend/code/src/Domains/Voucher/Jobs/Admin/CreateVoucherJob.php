<?php

namespace App\Domains\Voucher\Jobs\Admin;

use App\Domains\Voucher\Exports\VouchersExport;
use App\Domains\Voucher\Models\Voucher;
use App\Domains\Voucher\Mails\SendVoucherMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use INTCore\OneARTFoundation\Job;
use Log;
use Mail;

class CreateVoucherJob extends Job implements ShouldQueue
{

    use Queueable, SerializesModels;

    public $timeout = 1000;

    private $request;
    private $admin;
    /**
     * Create a new job instance.
     *
     * @param $note
     * @param $lesson_id
     */
    public function __construct($request, $admin)
    {
        $this->request = $request;
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     *
     *
     */
    public function handle()
    {

        for ($i = 0; $i < $this->request['number_vouchers']; $i++) {
            $data = $this->request;
            $data['voucher'] = $this->generateVoucher();
            $vouchers[] = Voucher::create($data);
        }

        $file = new VouchersExport($vouchers);

        Excel::store($file, 'voucher-' . date('Y-m-d') . '.xlsx', 'local');

        $file = public_path('voucher-' . date('Y-m-d') . '.xlsx');

        $this->sendEmail($this->admin, $vouchers, 1);

        unlink($file);
    }

    private function sendEmail($admin, $vouchers, $try)
    {
        $email = new SendVoucherMail($admin);

        try {
            Mail::to($admin->email)->send($email);
        } catch (\Exception $e) {
            if ($try < 3) {
                $this->sendEmail($admin, $vouchers, $try + 1);
            } else {
                foreach ($vouchers as $voucher) {
                    $voucher->delete();
                }
            }
        }

        Log::info('~ file: CreateVoucherJob.php:101 ~ try: ' . $try);
    }

    private function generateVoucher(): string
    {
        $prefix = 'CRD';
        $voucher = null;
        do {
            $voucher = $this->getRandomString($length = 27, $prefix);
        } while (Voucher::where('voucher', $voucher)->count() > 0);

        return $voucher;
    }

    public function getRandomString($length = 27, $prefix = '')
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return "{$prefix}{$randomString}";
    }
}
