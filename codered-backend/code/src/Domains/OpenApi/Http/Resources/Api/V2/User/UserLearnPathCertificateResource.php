<?php
namespace App\Domains\OpenApi\Http\Resources\Api\V2\User;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;


class UserLearnPathCertificateResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->learnPath->name ?? 'Learn Path Deleted',
            'certificate' => new FileResource($this->certificate),
        ];
    }
}
