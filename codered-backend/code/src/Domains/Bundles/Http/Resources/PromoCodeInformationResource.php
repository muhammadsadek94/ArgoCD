<?php

namespace App\Domains\Bundles\Http\Resources;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Bundles\Models\PromoCode;

class PromoCodeInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'                    => $this->id,
            'heading'               => $this->heading,
            'sub_heading'           => $this->sub_heading,
            'coupon_code'           => $this->coupon_code,
            'background_color'      => $this->background_color,
            'button_color'          => $this->button_color,
            'button_text_color'     => $this->button_text_color,
        ];
    }


}
