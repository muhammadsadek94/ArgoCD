<?php

namespace App\Domains\User\Http\Resources\Api\V1\User;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;

class TransactionResource extends JsonResource
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
            'order_id' => $this->order_id,
            'date'     => $this->created_at->format('M/Y'),
            'amount'   => $this->amount,
            'name'     => $this->payable->name ?? 'Item Not Exist',
            'pdf'      => url($this->pdf_url)
        ];
    }
}
