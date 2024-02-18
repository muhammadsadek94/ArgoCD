<?php

namespace App\Domains\Course\Features\Api\V2;

use App\Domains\Course\Http\Requests\Api\V2\GetSyllabusRequest;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class GetSyllabusFeature extends Feature
{

    public function handle(GetSyllabusRequest $request)
    {
        $ac = app('ac');

        $response = $ac->requestDownloadSyllabus($request->first_name, $request->last_name, $request->email, $request->phone, $request->course_name);

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'status' => true
            ]
        ]);


    }
}
