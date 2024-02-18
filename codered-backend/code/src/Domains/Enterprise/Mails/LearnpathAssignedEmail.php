<?php

namespace App\Domains\Enterprise\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LearnpathAssignedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $package_subscription;
    public $user;
    /**
     * @var string
     */
    public $head;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param        $package_subscription
     * @param mixed  $user
     * @param string $head
     */
    public function __construct(string $subject, $package_subscription, $user, $head = 'Thank you for your recent purchase on EC-Council Learning! Here are your EC-Council Learning Login details:')
    {
        $this->subject = $subject;
        $this->package_subscription = $package_subscription;
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
        return $this->subject($this->subject)->view('enterprise::mails.learnpath-assigned');
    }
}
