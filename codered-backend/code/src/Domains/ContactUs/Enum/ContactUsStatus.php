<?php


namespace App\Domains\ContactUs\Enum;

use App\Foundation\BasicEnum;

class ContactUsStatus extends BasicEnum
{
    const UNSEEN = 0;
    const SEEN = 1;
    const REPLIED = 2;
}
