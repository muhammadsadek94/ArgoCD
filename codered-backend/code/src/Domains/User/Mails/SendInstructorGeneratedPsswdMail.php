<?php

namespace App\Domains\User\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInstructorGeneratedPsswdMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param        $password
     * @param mixed  $user
     */
    public function __construct(string $subject, $password, $user)
    {
        $this->subject = $subject;
        $this->password = $password;
        $this->user = $user;

        /*print_r($user);
        die();*/
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('user::mails.instructor.generatedpassword');
    }
}
