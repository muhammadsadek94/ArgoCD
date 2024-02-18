<?php

namespace App\Domains\Course\Jobs\Common;

use App\Domains\Brightcove\API\CMS;
use App\Domains\Brightcove\API\DI;
use App\Domains\Brightcove\API\Request\IngestRequest;
use App\Domains\Brightcove\Item\Video\Video;
use App\Domains\Course\Enum\BrightCove;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Domains\Uploads\Models\Upload;
use FFMpeg;
use INTCore\OneARTFoundation\Job;
use Log;
use Str;
use Vimeo\Vimeo;

class UploadVideoToVimeoJob extends Job
{
    /**
     * @var Lesson
     */
    private $lesson;
    //    private $path;
    private $uri;
    private $client;
    /**
     * @var CMS|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $cms;

    /**
     * Create a new job instance.
     *
     * @param Lesson $lesson
     */
    public function __construct(Lesson $lesson, string $uri)
    {
        $this->client = new Vimeo(
        config('course.services.vimeo.client_id'),
        config('course.services.vimeo.client_secret'),
        config('course.services.vimeo.access_token')
    );
//        $this->path = $path;
        $this->uri = $uri;
        $this->lesson = $lesson;


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uri = $this->uri;
        $duration = $this->getVideoDuration($uri);
        $this->lesson->update([
            'video' => [
                'video_id' => $uri,
                'type' => \App\Domains\Course\Enum\VideoType::VIMEO
            ],
            // 'time' => $duration
        ]);
        $this->syncCourseDuration($this->lesson->course);
    }

    private function uploadVideoObject()
    {
        config()->set('filesystems.disks.azure.container', config('filesystems.disks.azure.container'));

        $video_upload = Upload::find($this->lesson->video['upload_id']);

        $video_path = getSasBlob($this->path, $video_upload->container);

        //        if (env('APP_ENV')   == 'local') {
        //            $video_path = "https://intcore.net/tmp/test-video.mp4";
        //        }

        $uri = $this->client->upload($video_path, [
            "name" => $this->lesson->name,
            "description" => $this->lesson->name
        ]);

        return $uri;
    }

    private function getVideoDuration($uri)
    {
        $video = $this->client->request($uri);
        return $video["body"]["duration"];
    }

    private function syncCourseDuration(Course $course)
    {
        dispatch(new SyncCourseDurationJob($course));
    }

}
