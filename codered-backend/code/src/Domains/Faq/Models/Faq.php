<?php

namespace App\Domains\Faq\Models;

use App\Domains\Faq\Enum\ActivationTypes;
use App\Domains\Faq\Enum\AppTypes;
use INTCore\OneARTFoundation\Model;

class Faq extends Model
{
    protected $fillable = ["question_en", "question_ar", "answer_en", "answer_ar", "activation", "app_type","type"];

    protected $attributes = [
        "app_type" => AppTypes::USER_APP
    ];
    public function scopeActive($query)
    {
        return $query->where("activation", ActivationTypes::ACTIVE);
    }

}
