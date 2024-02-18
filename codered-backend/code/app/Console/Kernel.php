<?php

namespace Framework\Console;

use App\Domains\Admin\Jobs\SaveLogFilesJob;
use App\Domains\Course\Console\DeleteExpiredAccessToCourses;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;
use App\Domains\Course\Console\DeleteExpiredMicroDegreeEnrollments;
use App\Domains\User\Jobs\Api\V2\User\Profile\UpdateDailyTargetJob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //        DeleteExpiredMicroDegreeEnrollments::class,
        DeleteExpiredAccessToCourses::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // testing purpose
        // $schedule->call(function () {
        //     Log::info('now is :' . now());
        // })->daily();

        // enable horizon matrices
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        // telescope data pruning
        $schedule->command('telescope:prune --hours=48')->daily();

        // auto delete expired micro degree expirations
//        $schedule->command('microdegree:delete-expired-enrollments')->daily();
        $schedule->command('course:delete-expired-enrollments')->daily();

        $schedule->command('update:lesson-time')->daily();

        $schedule->command('course:update-general-aggregations')->daily();

        // $schedule->job(UpdateDailyTargetJob::class)->weeklyOn(1, '00:00');

        $schedule->job(SaveLogFilesJob::class)->daily();

        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
