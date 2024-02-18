<?php

namespace App\Domains\ContactUs\Models;

use INTCore\OneARTFoundation\Model;

class ContactUsSubject extends Model
{
    const ACTIVE = 1;
    const InACTIVE = 0;
    protected $fillable = ["subject_en", "subject_ar", "activation"];

    protected $table = "contact_us_subjects";

    public function scopeActive($query)
    {
        $query->where("activation", self::ACTIVE);
    }
}
