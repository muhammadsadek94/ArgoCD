<?php

namespace Framework\Console\Commands;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Jobs\Common\SyncCourseDurationJob;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use Illuminate\Console\Command;
use Vimeo\Vimeo;

class UpdatePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:package';

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

        $packges = PackageSubscription::where('type', SubscriptionPackageType::CUSTOM)->get();
        foreach ($packges as $package) {
            $access_ids = $package->access_id;
            if (!is_array($access_ids) ){
                $access_ids = json_decode($package->access_id);
            }
                if ($access_ids != null)    {
                    foreach ($access_ids as $index => $course_id) {
                        $data   = [
                            'weight' => 0,
                            'course_id' => $course_id,
                            'package_subscription_id' => $package->id,
                            'sort' => 0,
                        ];
                        CourseWeight::updateOrCreate(
                            [
                                    'course_id' => $course_id,
                                    'package_subscription_id' => $package->id,
                            ],
                            [
                                'weight' => 0,
                                'course_id' => $course_id,
                                'package_subscription_id' => $package->id,
                                'sort' => 0,

                        ]);

                    }
                }
                else{
                }
//            $package->courses()->sync($data);
//            dd($package);
//            echo " " . gettype(json_decode($package->access_id)) . '\n';

        }
        echo "total videos: " . 'dpje' .'\n';
    }


}
