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

class UploadVideoToBrightCoveJob extends Job
{
    /**
     * @var Lesson
     */
    private $lesson;
    /**
     * @var CMS|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $cms;

    /**
     * Create a new job instance.
     *
     * @param Lesson $lesson
     */
    public function __construct(Lesson $lesson)
    {
        //
        $this->lesson = $lesson;
        $this->cms = app(CMS::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $video_object = $this->lesson->video ?? null;
        $video_id = $video_object['video_id'] ?? null;
        $brightcove_ref_id = $video_object['brightcove_ref_id'] ?? null;

        if (is_null($video_object) || is_null($video_id)) {
            $video_object = $this->createVideObject();
            $video_id = $video_object['video_id'];
            $brightcove_ref_id = $video_object['brightcove_ref_id'];
            $this->addVideoToFolder($video_id);
        }

        $this->uploadVideoObject($video_id);

        $duration = $this->getVideoDuration();
        $this->lesson->update([
            'video' => [
                'video_id' => $video_id,
                'brightcove_ref_id' => $brightcove_ref_id,
                'type'=> \App\Domains\Course\Enum\VideoType::BRIGHTCOVE
            ],
            'time'  => $duration
        ]);

        $this->syncCourseDuration($this->lesson->course);
    }

    private function createVideObject()
    {
        $videoModel = new Video();
        $videoModel->setName($this->lesson->name);
        //        $videoModel->setDescription($this->lesson->overview);//MARK: get exception too long from brightcove api
        $reference_id = Str::uuid();
        $videoModel->setReferenceId($reference_id);
        $createVideo = $this->cms->createVideo($videoModel);

        return [
            'brightcove_ref_id' => $reference_id,
            'video_id' => $createVideo->getId()
        ];

    }

    private function uploadVideoObject(string $video_id)
    {
        config()->set('filesystems.disks.azure.container', config('filesystems.disks.azure.container'));

        $video_upload = Upload::find($this->lesson->video['upload_id']);

            $video_path = getSasBlob($video_upload->path, $video_upload->container);

        if (env('APP_ENV') == 'local') {
            $video_path = "https://intcore.net/tmp/test-video.mp4";
        }

        $request = IngestRequest::createRequest(
            $video_path,
            BrightCove::PLAYER_PROFILE()
        );

        return app(DI::class)->createIngest($video_id, $request);
    }

    private function addVideoToFolder(string $video_id)
    {
        return $this->cms->addVideoToFolder($video_id, BrightCove::GETFOLDERID());
    }

    private function getVideoDuration()
    {
        $path = $this->lesson->video['video_file'];
        $media = FFMpeg::open($path);
        return $media->getDurationInSeconds();
    }

    private function syncCourseDuration(Course $course)
    {
        dispatch(new SyncCourseDurationJob($course));
    }

}
