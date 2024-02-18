<?php

namespace Framework\Console\Commands;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Jobs\Common\SyncCourseDurationJob;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use Illuminate\Console\Command;
use Vimeo\Vimeo;

class UpdateVimeoTimeJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:lesson-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $vimeo_player = new Vimeo(
            config('course.services.vimeo.client_id'),
            config('course.services.vimeo.client_secret'),
            config('course.services.vimeo.access_token')
        );
        $lessons = Lesson::where('type', LessonType::VIDEO)
            ->where(function($query) {
                return $query->where('time', 0)->orWhereNull('time');
            })->get();

            echo "total videos: ". count($lessons) . '\n';
        foreach ($lessons as $key => $lesson) {
            $video = $this->getVideoData($lesson->video);
            if ($video['type'] == 'vimeo') {
                $response = $vimeo_player->request($video['video_id']);
                $duration = $response['body']['duration'];
                $lesson->time = $duration;
                $lesson->save();
                $rem = count($lessons) - $key;
                echo "{$lesson->name} - {$lesson->time}  - remaining : {$rem} \n";
            }
        }
        $this->syncCourseDuration($lesson->course);
    }
    private function syncCourseDuration(Course $course)
    {
        dispatch(new SyncCourseDurationJob($course));
    }

    private function getVideoData($video)
    {
        return [
            'account_id' => @$video['account_id'],
            'player_id'  => @$video['player_id'],
            'video_id'   => @$video['video_id'],
            'type'   => @$video['type'],
        ];
    }
}
