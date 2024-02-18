<?php

namespace App\Domains\Payments\Http\Resources\Api\V1\User;

use INTCore\OneARTFoundation\Http\JsonResource;

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
            'name'     => $this->payable?->name ?? 'Item Not Exist',
            'pdf'      => url($this->pdf_url)
        ];
    }
}
