<?php

namespace App\Domains\ContactUs\Rules;

use App\Foundation\BasicEnum;

class ContactUsPermission extends BasicEnum
{
    const MODULE = 'Contact Us';

    const CONTACTUS_INDEX = [
        "name"    => "Contact us list",
        "ability" => "contact_us.index"
    ];


    const CONTACTUS_REPLY = [
        "name"    => "View Message & Reply",
        "ability" => "contact_us.show",
    ];

    const CONTACTUS_DELETE = [
        "name"    => "Delete",
        "ability" => "contact_us.delete"
    ];

}
