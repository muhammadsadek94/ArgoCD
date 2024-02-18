<?php

namespace App\Domains\Course\Jobs\Common;

use INTCore\OneARTFoundation\Job;
use App\Domains\Brightcove\API\CMS;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Enum\BrightCove;

class DeleteVideoFromBrightCoveJob extends Job
{
    /**
     * @var Lesson
     */
    private $lesson;

    /**
     * Create a new job instance.
     *
     * @param Lesson $lesson
     */
    public function __construct(Lesson $lesson)
    {
        //
        $this->lesson = $lesson;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $video = $this->lesson->video;
        $video_id = null;

        if($video && $video['video_id']) {
            $video_id = $video['video_id'];
        }

        if(is_null($video_id)) return;

        app(CMS::class)->deleteVideo($video_id);

        dispatch(new SyncCourseDurationJob($this->lesson->course));

    }


}
