<?php

namespace App\Domains\Course\Jobs\Api\V1\User;

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use INTCore\OneARTFoundation\Job;
use App\Domains\Course\Models\LessonUserNote;

class UpdateNoteJob extends Job
{


    private $note_id;
    private $note;


    /**
     * Create a new job instance.
     *
     * @param $note
     * @param $note_id
     */
    public function __construct($note_id,$note)
    {
        $this->id   =  $note_id;
        $this->note =  $note;
    }

   /**
     * Execute the job.
     *
     *
     */
    public function handle()
    {

      LessonUserNote::find($this->id)->update([
            'note'      => $this->note,
        ]);

        return LessonUserNote::find($this->id);
    }
}
