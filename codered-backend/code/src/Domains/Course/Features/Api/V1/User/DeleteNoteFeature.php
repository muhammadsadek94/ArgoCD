<?php

namespace App\Domains\Course\Features\Api\V1\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Models\LessonUserNote;
use App\Domains\Course\Jobs\Api\V1\User\DeleteNoteJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Http\Resources\Api\V1\Lesson\LessonNoteResource;


class DeleteNoteFeature extends Feature
{

    public function handle(Request $request)
    {


        if (LessonUserNote::where('id', $request->note_id)->doesntExist()) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => "This record doesn't exist!!Cannot delete",
                ]
            ]);
        }

        $noteobj = $this->run(DeleteNoteJob::class, [
            'note_id'      => $request->note_id,      //note_id received from url
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'message' => 'success'
            ]
        ]);

    }
}
