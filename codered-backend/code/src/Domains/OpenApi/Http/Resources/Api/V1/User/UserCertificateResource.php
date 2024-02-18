<?php
namespace App\Domains\OpenApi\Http\Resources\Api\V1\User;

use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V1\Instructor\InstructorResource;
use App\Domains\User\Http\Resources\Api\V1\User\UserBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;


class UserCertificateResource extends JsonResource
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
            'name'        => $this->course->name ?? 'Course Deleted',
            'instructor'  => new UserBasicInfoResource($this->course->instructors()->first()) ?? 'Course Deleted',
            'category'    => $this->course ? new CourseCategoryResource($this->course->category) : 'Not Available',
            'time'        => $this->course->timing ?? 'Not Available',
            'level'       => $this->course->level ?? 'Not Available',
            'certificate' => new FileResource($this->certificate),
            'degree'      => $this->degree,
            'type'        => $this->course ? $this->course->course_type : null,
        ];
    }
}
