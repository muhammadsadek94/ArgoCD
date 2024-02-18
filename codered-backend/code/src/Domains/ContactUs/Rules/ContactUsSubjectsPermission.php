<?php

namespace App\Domains\ContactUs\Rules;

use App\Foundation\BasicEnum;

class ContactUsSubjectsPermission extends BasicEnum
{
    const MODULE = 'Contact Us \'s Subjects ';

    const CONTACTUS_SUBJECTS_INDEX = [
        "name"    => "Contact us's subjects list",
        "ability" => "contact_us_subjects.index"
    ];

    const CONTACTUS_SUBJECTS_CREATE = [
        "name"    => "Create",
        "ability" => "contact_us_subjects.create"
    ];

    const CONTACTUS_SUBJECTS_EDIT = [
        "name"    => "Edit",
        "ability" => "contact_us_subjects.edit",
    ];

    const CONTACTUS_SUBJECTS_DELETE = [
        "name"    => "Delete",
        "ability" => "contact_us_subjects.delete"
    ];

}
