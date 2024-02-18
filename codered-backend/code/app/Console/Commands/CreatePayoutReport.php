<?php

namespace Framework\Console\Commands;

use App\Domains\Configuration\Enum\AccessType;
use App\Domains\Configuration\Enum\SubscriptionPackageType;
use App\Domains\Course\Models\Course;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Export\ReceiptExport;
use App\Domains\User\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class CreatePayoutReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout:create-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create report for payout all instructors';

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

        $quarter = 4;
        $year = 2021;
        $billable_revenue = 36.747;

        $timeframe = $this->getQuarter($quarter, $year);

        $start_date = $timeframe['start_date'];
        $end_date = $timeframe['end_date'];

        $coruses_pro = Course::query()
            ->leftJoin('watch_history_times', function ($join) use ($start_date, $end_date) {
                $join->on('watch_history_times.course_id', '=', 'courses.id')
                    ->where('watch_history_times.created_at', '>=', $start_date)
                    ->where('watch_history_times.created_at', '<=', $end_date);
            })
            ->join('user_subscriptions', function ($join) {
                $join->on('user_subscriptions.user_id', '=', 'watch_history_times.user_id')
                    ->where(function ($query) {
                        $query->whereColumn('user_subscriptions.created_at', '<=', 'watch_history_times.created_at');
                        $query->whereColumn('user_subscriptions.expired_at', '>=', 'watch_history_times.created_at');
                    })
                    ->join('package_subscriptions', function ($join) {
                        $join->on('package_subscriptions.id', '=', 'user_subscriptions.package_id')
                            ->whereIn('package_subscriptions.access_type', [AccessType::PRO]);
                    });
            })
            ->selectRaw('courses.name,courses.id ,courses.commission_percentage,sum(watch_history_times.watched_time)/60 as proTime')
            ->groupBy('courses.id')
            ->get();

        $corusesAllTime = Course::query()
            ->leftJoin('watch_history_times', function ($join) use ($start_date, $end_date) {
                $join->on('watch_history_times.course_id', '=', 'courses.id')
                    ->where('watch_history_times.created_at', '>=', $start_date)
                    ->where('watch_history_times.created_at', '<=', $end_date);
            })
            ->join('user_subscriptions', function ($join) {
                $join->on('user_subscriptions.user_id', '=', 'watch_history_times.user_id')
                    ->where(function ($query) {
                        $query->whereColumn('user_subscriptions.created_at', '<=', 'watch_history_times.created_at');
                        $query->whereColumn('user_subscriptions.expired_at', '>=', 'watch_history_times.created_at');
                    })
                    ->join('package_subscriptions', function ($join) {
                        $join->on('package_subscriptions.id', '=', 'user_subscriptions.package_id')
                            ->whereIn('package_subscriptions.access_type', [AccessType::COURSES,AccessType::COURSE_CATEGORY,AccessType::PRO]);
                    });
            })
            ->selectRaw('courses.name,courses.id,courses.commission_percentage,courses.user_id,sum(watch_history_times.watched_time)/60 as allTime')
            ->groupBy('courses.id')
            ->get();

        // generate report name
        $report_name = "{$quarter}-{$year}-" . Carbon::now()->timestamp . '.xlsx';
        $report = new ReceiptExport($corusesAllTime, $coruses_pro, $quarter, $year, $billable_revenue);

        return Excel::store($report, $report_name, 'local');


    }

    private function getQuarter($quarter, $year)
    {
        $quarters = [
            1 => [
                'start_date' => '{{year}}-01-01',
                'end_date'   => '{{year}}-03-31',
            ],
            2 => [
                'start_date' => '{{year}}-04-01',
                'end_date'   => '{{year}}-06-30',
            ],
            3 => [
                'start_date' => '{{year}}-07-01',
                'end_date'   => '{{year}}-09-30',
            ],
            4 => [
                'start_date' => '{{year}}-10-01',
                'end_date'   => '{{year}}-12-31',
            ]
        ];

        $selected_quarter = $quarters[$quarter];

        $start_date = $selected_quarter['start_date'];
        $end_date = $selected_quarter['end_date'];

        $start_date = str_replace('{{year}}', $year, $start_date);
        $end_date = str_replace('{{year}}', $year, $end_date);

        return [
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ];
    }

}
