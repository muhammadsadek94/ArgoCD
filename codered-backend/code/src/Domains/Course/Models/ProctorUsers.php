<?php

namespace App\Domains\Course\Models;

use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;

class ProctorUsers extends Model
{
    use Loggable;

    protected $fillable = ['username', 'password', 'activation', 'course_ids'];

    protected $hidden = ['password'];
    protected $casts = [
        'course_ids' => 'array'
    ];

    public function setPasswordAttribute(?string $password)
    {
        if (!empty($password))
            $this->attributes['password'] = bcrypt($password);
    }
    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }

}
