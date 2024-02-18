<?php

namespace App\Domains\Payments\Http\Resources\Api\V1\User;

use INTCore\OneARTFoundation\Http\JsonResource;

class PackageResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'amount'       => $this->amount,
            'type'         => $this->type,
            'description'  => $this->description,
            'url'          => $this->url,
            'subscription' => $this->when(!!$request->user('api'), function () use ($request) {
                $user = $request->user('api');
                $status = true;
                $expiration = null;

                if (!$user->hasActiveSubscription()) $status = false;

                if ($status) {
                    $active_subscription = $request->user('api')->active_subscription()->where('package_id', $this->id)->first();
                    $status = !!$active_subscription;
                    $expiration = $active_subscription ? $active_subscription->expired_at : null;
                }

                return [
                    'status' => $status,
                    'expiration' => $expiration,
                ];
            })
        ];
    }
}
