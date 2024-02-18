<?php

namespace App\Domains\Payments\Models;

use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;

class LearnPathPackage extends Model
{
    use Loggable;

    protected $fillable = ['name','amount','type','features','url','path_id'];

    protected $casts = [
        'features' => 'array'
    ];


}
