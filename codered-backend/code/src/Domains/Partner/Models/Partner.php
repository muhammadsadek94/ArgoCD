<?php

namespace App\Domains\Partner\Models;

use App\Domains\Course\Models\Course;
use INTCore\OneARTFoundation\Model;

class Partner extends Model
{
    protected $fillable = ['name', 'partner_name', 'partner_secret', 'activation'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_partner');
    }

    public function setPartnerSecretAttribute(?string $secret)
    {
        if (!empty($secret))
            $this->attributes['partner_secret'] = bcrypt($secret);
    }
}
