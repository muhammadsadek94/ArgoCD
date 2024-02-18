<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Jobs\Common\SyncCourseDurationJob;
use App\Domains\Course\Jobs\Common\UploadVideoToVimeoJob;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use INTCore\OneARTFoundation\Http\Controller;
use FFMpeg;
use Vimeo\Vimeo;

class VimeoController extends CoreController
{
    use HasAuthorization;

    /**
     * @var Vimeo
     */
    private $vimeo_player;

    /**
     */

    public function __construct(Lesson $model)
    {
        $this->model = $model;

        $this->vimeo_player = new Vimeo(
            config('course.services.vimeo.client_id'),
            config('course.services.vimeo.client_secret'),
            config('course.services.vimeo.access_token')
        );

        parent::__construct();

    }

    /**
     * upload video to vimeo
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function upload(Request $request, $id)
    {
        $this->validate($request, [
            'video.file' => ['required', 'mimes:mp4']
        ]);
        $row = $this->model->find($id);
        $row->update([
            'video' => $request->video
        ]);
        $this->getVideoDuration($row);

        $uri = $this->vimeo_player->upload($request->video['file']->path(), [
            "name" => $row->name,
            "description" => $row->name
        ]);

        dispatch_now(new UploadVideoToVimeoJob($row, $uri));

        return $this->returnMessage(true, 0, ['status' => "true"]);
    }

    private function getVideoDuration($lesson)
    {
        $path = $lesson->video['video_file'];
        $media = FFMpeg::open($path);
        $duration = $media->getDurationInSeconds();
        Lesson::where('id',$lesson->id)->update([ 'time'  => (string)$duration]);

        $this->syncCourseDuration($lesson->course);
    }

    private function syncCourseDuration(Course $course)
    {
        dispatch(new SyncCourseDurationJob($course));
    }

    public static function getCaptionData()
    {
        $vimeo_player = new Vimeo(
            config('course.services.vimeo.client_id'),
            config('course.services.vimeo.client_secret'),
            config('course.services.vimeo.access_token')
        );
        $response = $vimeo_player->request('/languages?filter=texttracks');
        $data = $response['body']['data'];

        $data_collection = collect($data)->mapWithKeys(function ($item) {
            return [$item['code'] => $item['name']];
        })->all();

        return $data_collection;
    }

    public function getVideoCaption($id)
    {

        $row = $this->model->find($id);
        $response = $this->vimeo_player->request($row->video['video_id'] . '/texttracks');
        $data = $response['body']['data'];
//        $data_collection = collect($data)->mapWithKeys(function($item) {
//            return  [$item['code'] => $item['name']];
//        })->all();

        return $data;
    }

    /**
     * save caption to  video to vimeo
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function saveCaption(Request $request, $id)
    {
        $this->validate($request, [
            'video.file' => ['required', 'mimes:txt,mimes:srt']
        ]);
        $row = $this->model->find($id);
        $track_path = $row->video['video_id'] . '/texttracks';
        try {

            $uri = $this->vimeo_player->uploadTexttrack($track_path, $request->video['file']->path(), 'subtitles', $request->caption_id);

        } catch (\Exception $e) {

            return $this->returnMessage(false, 0, ['failed' => 'Please Check The file Caption format']);

        }
        return $this->returnMessage(true, 0);

    }

    public function updateCaption(Request $request)
    {
        $uri = $this->vimeo_player->request($request->uri, ['active' => $request->status], 'PATCH');
        return $uri;
    }


}
