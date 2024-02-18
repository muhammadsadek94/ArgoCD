<?php

namespace Framework\Console\Commands;

use App\Domains\Course\Models\CourseEnrollment;
use DB;
use Illuminate\Console\Command;

class DeleteDuplicateEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:duplicated-enrollments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete duplicated enrollments but keep the first one';

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
        $oldDuplicatedRecords = DB::table("course_enrollment")
            ->whereNotNull('user_id')
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->course_id . $item->user_id;
            })
            ->filter(function ($item) {
                return $item->count() > 1;
            })
            ->map(function ($item) {
                return $item->sortBy('created_at')->values()->first();
            })
            ->pluck('id')
            ->toArray();

        $allDuplication = DB::table("course_enrollment")
            ->whereNotNull('user_id')
            ->groupBy("course_id", "user_id")
            ->havingRaw('count(id) > ?', [1])
            ->get();

        $allDuplication->each(function ($item, $key) use ($oldDuplicatedRecords) {
            CourseEnrollment::where('course_id', $item->course_id)
                ->where('user_id', $item->user_id)
                ->whereNotIn('id', $oldDuplicatedRecords)
                ->delete();
        });
    }
}
