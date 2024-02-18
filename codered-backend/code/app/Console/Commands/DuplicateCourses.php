<?php

namespace Framework\Console\Commands;

use App\Domains\Payments\Enum\AccessType;
use App\Domains\Course\Models\Course;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\UserSubscription;
use Illuminate\Console\Command;

class DuplicateCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:duplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'duplicate courses and their data';

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
        ini_set('memory_limit', '6400M');



        $courses = Course::whereIn('id', ['fa424a18-1c50-4a37-b503-a7e2af26abfe', '8368e9c4-3acc-4a7e-a8fc-e5aead584be9', 'c3febcc5-10e0-4b36-bfa2-7183d60cd2bb', '00479b0b-fe8a-41b8-8f19-097d7ad21bcd'])
            ->with(['chapters', 'lessons', 'image', 'cover', 'instructors', 'user', 'category', 'assessments', 'tags', 'partners'])
            ->get();
        $newModels = collect();


        foreach ($courses as $course) {
            $newModel = $course->replicate();
            $newModel->push();


            foreach ($course->getRelations() as $relation => $items) {
                dd(get_class($items));
                foreach ($items as $item) {


                }
            }

//            $newModels->push($newModel->id);
        }

//        dd($newModels);
    }

    public function recreate($model) {
        foreach ($model->getRelations() as $relation => $items) {

            foreach ($items as $item) {
                $newModel = $model->replicate();
                $newModel->push();

            }
        }

    }
}
