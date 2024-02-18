<?php

namespace App\Domains\Faq\Http\Resources\Api\V1;

use INTCore\OneARTFoundation\Http\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'question' => $this->question_en,
            'answer' => $this->answer_en,
            'type' => $this->type,
            'app_type' => $this->app_type,
            'activation' => $this->activation,
        ];
        // return parent::toArray($request);
    }
}
