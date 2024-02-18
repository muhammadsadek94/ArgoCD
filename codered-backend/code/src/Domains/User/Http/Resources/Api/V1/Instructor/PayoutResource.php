<?php

namespace App\Domains\User\Http\Resources\Api\V1\Instructor;

use App\Domains\User\Enum\PayoutStatus;
use INTCore\OneARTFoundation\Http\JsonResource;

class PayoutResource extends JsonResource
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
            'id'         => $this->secondary_id,
            'name'       => $this->name,
            'course_name' => $this->course ? $this->course->name : null,
            'period'     => $this->period,
            'type'       => $this->type,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'status'     => $this->status,
            'created_at' => $this->created_at->format('d M Y'),
            'amount'     => (int)$this->royalty + (int)$this->royalties_carried_out - (int)$this->outstanding_advances,
            'pdf'        => $this->when(in_array($this->status, [PayoutStatus::APPROVED, PayoutStatus::PAID]), function() {
                return $this->attachment->full_url ?? null;
            })
        ];
    }
}
