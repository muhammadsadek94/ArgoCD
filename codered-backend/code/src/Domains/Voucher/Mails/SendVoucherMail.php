<?php

namespace App\Domains\Voucher\Mails;

use Illuminate\Mail\Mailable;

class SendVoucherMail extends Mailable
{

    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('voucher::emails.voucher')->attach(public_path('voucher-' . date('Y-m-d') . '.xlsx'));
    }
}
