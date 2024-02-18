<?php

namespace App\Domains\Course\Console;

use App\Domains\Course\Models\CourseEnrollment;
use Illuminate\Console\Command;
use DB;
use App\Domains\Course\Enum\CourseType;

class DeleteExpiredAccessToCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:delete-expired-enrollments';

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
        $expiration = now();

        $query = CourseEnrollment::whereNotNull('expired_at')
            ->where('expired_at', '<', $expiration);


        $count = $query->count();
        $query->delete();
        echo "{$count} enrollments deleted. \n";

        return;

    }
}
