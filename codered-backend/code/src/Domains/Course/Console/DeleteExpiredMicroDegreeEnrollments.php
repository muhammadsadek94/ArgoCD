<?php

namespace App\Domains\Course\Console;

use Illuminate\Console\Command;
use DB;
use App\Domains\Course\Enum\CourseType;

/**
 * @deprecated
 * Class DeleteExpiredMicroDegreeEnrollments
 *
 * @package App\Domains\Course\Console
 */
class DeleteExpiredMicroDegreeEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microdegree:delete-expired-enrollments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will delete expired enrollments [more than 1 year]';

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
     * @return mixed
     */
    public function handle()
    {
        $expiration = now()->subYear();

        $micro_degrees_id = DB::table('courses')
            ->where('course_type', CourseType::MICRODEGREE)
            ->pluck('id')->toArray();

        $query = DB::table('course_enrollment')
            ->where('created_at', '<=', $expiration)
            ->whereIn('course_id', $micro_degrees_id);

        $count = $query->count();
        $query->delete();
        echo "{$count} enrollments deleted. \n";

        return;

    }
}
