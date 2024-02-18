<?php

namespace App\Domains\Enterprise\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $user;
    /**
     * @var string
     */
    public $head;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param        $password
     * @param mixed  $user
     * @param string $head
     */
    public function __construct(string $subject, $password, $user, $head = 'Thank you for your recent purchase on EC-Council Learning! Here are your EC-Council Learning Login details:')
    {
        $this->subject = $subject;
        $this->password = $password;
        $this->user = $user;
        $this->head = $head;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('learnersupport@eccouncil.org', 'EC-Council Enterprise')
            ->subject($this->subject)
            ->view('enterprise::mails.password');
    }
}
