<?php

namespace App\Domains\Course\Jobs\Api\V1\User;

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use INTCore\OneARTFoundation\Job;
use App\Domains\Course\Models\LessonUserNote;

class DeleteNoteJob extends Job
{


    private $note_id;


    /**
     * Create a new job instance.
     *
     * @param $note
     * @param $lesson_id
     */
    public function __construct($note_id)
    {
        $this->id = $note_id;
    }

   /**
     * Execute the job.
     *
     *
     */
    public function handle()
    {

      return LessonUserNote::where([
            'id' => $this->id,
        ])->delete();
    }
}
