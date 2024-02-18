<?php

namespace App\Domains\ContactUs\Events;

use App\Domains\ContactUs\Models\ContactUs;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewInquiry
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var ContactUs
     */
    public $contact_us;

    /**
     * Create a new event instance.
     *
     * @param ContactUs $contact_us
     */
    public function __construct(ContactUs $contact_us)
    {
        $this->contact_us = $contact_us;
    }


}
