<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use App\Domains\User\Http\Resources\Api\V1\User\UserBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class ReviewResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->user ? $this->user->first_name : "",
            'rate'              => $this->rate,
            'recommendation'    => $this->recommendation,
            'user'              => new UserBasicInfoResource($this->user),
            'user_goals'        => $this->user_goals,
        ];
    }


    public function getAnswers(): array
    {
        $answers = [];

        foreach ($this->answers as $answer) {
            $data = [
                'id' => $answer->id,
                'answer' => $answer->answer
            ];
            $answers[] = $data;
        }

        return $answers;
    }
}
