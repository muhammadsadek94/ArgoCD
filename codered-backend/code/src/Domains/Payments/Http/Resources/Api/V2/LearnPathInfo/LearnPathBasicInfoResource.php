<?php

namespace App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo;

use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Payments\Enum\LearnPathType;

use INTCore\OneARTFoundation\Http\JsonResource;

class LearnPathBasicInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {


        $user = $request->user('api');
       /* $certificate = null;
        if ($user) {
            $cert_check = $this->allPathCertificates->where('user_id', $user->id)->first();
            if ($cert_check) {
                $certificate = $cert_check->id;
            }
        }*/

        $countCourses = @$this->agg_courses['total_courses'] ?? 0;

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'slug_url'              => $this->slug_url,
            'description'           => $this->description,
            'features'              => $this->features,
            'type'                  => (int) $this->type,
            'timing'                => @$this->agg_courses['duration_human_text'] ?? 0,
            'courses_count'         => $countCourses,
            'type_name'             => LearnPathType::getTypeName((int) $this->type),
            'completion_percentage' => $countCourses ? $this->completionPercentage($request) : 0,
            'certificate'           => $this->whenLoaded('allPathCertificates', function () {
                return $this->allPathCertificates->first()?->id;
            }),
        ];


    }

    private function completionPercentage($request)
    {
        try {
            if (!$request->user('api')) return 0;
            $countCourses = @$this->agg_courses['total_courses'] ?? 0;
            $completed_courses = (int)$this->whenLoaded('completedCourses', function () {
                return (int)$this->completedCourses->count();
            });
            return round((int)($completed_courses / $countCourses) * 100);
        } catch (\Exception $e) {
            // temporary fix for exception: ErrorException Object of class Illuminate\Http\Resources\MissingValue could not be converted to int
            return 0;
        }

    }

}
