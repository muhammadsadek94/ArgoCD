<?php

namespace App\Domains\ContactUs\Models;

use App\Domains\ContactUs\Events\NewInquiry;
use INTCore\OneARTFoundation\Model;
use App\Domains\User\Models\User;
use App\Domains\ContactUs\Enum\ContactUsStatus;

/**
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed email
 * @property mixed phone
 */
class ContactUs extends Model
{

    protected $table = "contact_uses";

    protected $fillable = [
        'first_name', 'last_name', "phone", "email", "subject_id", "body", "status", "user_id", "app_type"
    ];
    protected $hidden = ['user_id'];

    protected $attributes = [
        'status'     => ContactUsStatus::UNSEEN,
        'last_name'  => null,
        'phone'      => null,
        'subject_id' => null,
    ];

    protected $dispatchesEvents = [
        'created' => NewInquiry::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->hasOne(ContactUsSubject::class, "id", "subject_id");
    }
}


