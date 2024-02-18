<?php

namespace App\Domains\User\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCommentMail extends Mailable
{
    use SerializesModels;

    public $subject;
    public $userComment;
    public $user;
    public $admin;
    public $head;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param mixed  $comment
     * @param mixed  $user
     * @param mixed  $admin
     * @param mixed $head
     */
    public function __construct(string $subject, $comment, $user,$admin, $head = '')
    {
        $this->subject = $subject;
        $this->userComment = $comment;
        $this->user = $user;
        $this->admin = $admin;
        $this->head = $head;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('user::mails.comment');
    }
}
