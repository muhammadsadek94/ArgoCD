<?php

namespace App\Domains\User\Jobs\Admin\Instructor;

use App\Domains\Configuration\Enum\SubscriptionPackageType;
use App\Domains\Course\Models\Course;
use App\Domains\User\Export\ReceiptExport;
use App\Domains\User\Mails\ExportPayoutExcelSheetMail;
use App\Domains\User\Mails\SendInstructorGeneratedPsswdMail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use INTCore\OneARTFoundation\Job;
use Maatwebsite\Excel\Facades\Excel;

class PayoutExcelExportJob extends Job implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;
    /**
     * @var array
     */
    private $request;

    private $timeout = 3600;
    private $admin;

    /**
     * Create a new job instance.
     *
     * @param array $request
     * @param $admin
     */
    public function __construct(array $request, $admin)
    {
        $this->request = $request;
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $timeframe = $this->getQuarter();

        $start_date = $timeframe['start_date'];
        $end_date = $timeframe['end_date'];
        $quarter = $this->request['quarter'];
        $coruses_pro = Course::query()
            ->leftJoin('watch_history_times', function ($join)use ($start_date, $end_date) {
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
                            ->whereIn('package_subscriptions.type', [SubscriptionPackageType::ANNUAL, SubscriptionPackageType::MONTHLY]);
                    });
            })
            ->selectRaw('courses.name,courses.id ,courses.commission_percentage,sum(watch_history_times.watched_time)/60 as proTime')
            ->groupBy('courses.id')
            ->get();


        $corusesAllTime = Course::query()
            ->leftJoin('watch_history_times', function ($join) use ($start_date, $end_date){
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
                            ->whereIn('package_subscriptions.type', [SubscriptionPackageType::ANNUAL, SubscriptionPackageType::MONTHLY, SubscriptionPackageType::CUSTOM]);
                    });
            })
            ->selectRaw('courses.name,courses.id,courses.commission_percentage,sum(watch_history_times.watched_time)/60 as allTime')
            ->groupBy('courses.id')->get();
        $file = Excel::download(
            new ReceiptExport(
                $corusesAllTime, $coruses_pro, $quarter ,Carbon::now()->year,
                $this->request['billable_revenue']), "$start_date-report-$end_date.xlsx"
        )->getFile();


        Mail::to($this->admin->email)->send(new ExportPayoutExcelSheetMail($file));
        return true;
    }

    private function getQuarter(): array
    {
        $quarters = [
            1 => [
                'start_date' => '{{year}}-01-01',
                'end_date' => '{{year}}-03-31',
            ],
            2 => [
                'start_date' => '{{year}}-04-01',
                'end_date' => '{{year}}-06-30',
            ],
            3 => [
                'start_date' => '{{year}}-07-01',
                'end_date' => '{{year}}-09-30',
            ],
            4 => [
                'start_date' => '{{year}}-10-01',
                'end_date' => '{{year}}-12-31',
            ]
        ];

        $selected_quarter = $quarters[$this->request['quarter']];

        $start_date = $selected_quarter['start_date'];
        $end_date = $selected_quarter['end_date'];

        $start_date = str_replace('{{year}}', $this->request['year'], $start_date);
        $end_date = str_replace('{{year}}', $this->request['year'], $end_date);

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }

}
