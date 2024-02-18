<?php

namespace App\Domains\Course\Models;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class BookAccess extends Model
{
    protected $fillable = ['id','user_id','book_id','code'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
