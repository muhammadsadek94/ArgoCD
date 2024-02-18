<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Http\Requests\Api\InteractWithLessonTaskRequest;
use App\Domains\Course\Jobs\Api\V1\User\InteractWithLessonTaskJob;
use App\Foundation\Traits\Authenticated;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class InteractWithLessonTaskFeature extends Feature
{
    use Authenticated;

    public function handle(Request $request)
    {
        $this->run(InteractWithLessonTaskJob::class, [
            'user' => $request->user('api'),
            'is_checked' => (bool)$request->is_checked,
            'lesson_id' => $request->lesson_id,
            'lesson_task_id' => $request->lesson_task_id
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'is_checked' => (bool)$request->is_checked
            ]
        ]);
    }
}
