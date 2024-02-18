<?php

namespace App\Domains\User\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExportPayoutExcelSheetMail extends Mailable
{
    use Queueable, SerializesModels;

    private $file;

    /**
     * Create a new message instance.
     *
     * @param $excel
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Instructors Payout Sheet')
            ->view('user::mails.excel-export')
            ->attach($this->file, ['as' => 'report2.xlsx', 'mime' => $this->file->getMimeType()]);
    }
}
