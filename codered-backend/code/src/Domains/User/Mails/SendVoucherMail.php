<?php

namespace App\Domains\User\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendVoucherMail extends Mailable
{
    use  SerializesModels;

    public $code;
    public $user;
    public $instructions;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param mixed $code
     * @param mixed $user
     */
    public function __construct(string $subject, $code, $user,$instructions)
    {
        $this->subject = $subject;
        $this->code = $code;
        $this->user = $user;
        $this->instructions = $instructions;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('user::mails.Voucher');
    }
}
