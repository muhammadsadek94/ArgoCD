<?php

namespace App\Domains\User\Http\Controllers\Admin;

use App\Domains\Payments\Enum\AccessType;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Export\ReceiptExport;
use App\Domains\User\Enum\PayoutStatus;
use App\Domains\User\Enum\PayoutType;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Jobs\Admin\Instructor\PayoutExcelExportJob;
use App\Domains\User\Jobs\Admin\Instructor\PayoutPDFExportJob;
use App\Domains\User\Jobs\Api\V1\Instructor\Payouts\CreatePayoutInvoiceJob;
use App\Domains\User\Models\Instructor\Payout;
use App\Domains\User\Models\Instructor\PayoutRoyalty;
use App\Domains\User\Models\User;
use App\Domains\User\Rules\PayoutPermission;
use Carbon\Carbon;
use ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Foundation\Http\Controllers\Admin\CoreController;
use http\Env\Response;
use phpDocumentor\Reflection\DocBlock\Tags\Covers;
use Maatwebsite\Excel\Facades\Excel;
use MPDF;
use Mpdf\Mpdf as MpdfMpdf;
use Storage;

use function React\Promise\all;

class PayoutController extends CoreController
{
    use HasAuthorization;

    public $domain = "user";

    public function __construct(Payout $model)
    {
        $this->model = $model;

        $this->select_columns = [
            [
                'name' => 'Course Name',
                'type' => ColumnTypes::CALLBACK,
                'key'  => function ($row) {
                    return $row->course?->internal_name ?? $row->course?->name;
                },
            ],
            [
                'name' => 'Quarter/Date',
                'type' => ColumnTypes::CALLBACK,
                'key'  => function ($row) {
                    return $row->type == PayoutType::QUARTER ? $row->name : $row->start_date . ' - ' . $row->end_date;
                },
            ],
            [
                'name' => 'New Balance',
                'type' => ColumnTypes::CALLBACK,
                'key'  => function ($row) {
                    return (int)$row->royalty - (int)$row->outstanding_advances + (int)$row->royalties_carried_out;
                },
            ],
            [
                'name' => trans("Outstanding advances"),
                'key' => 'outstanding_advances',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Status"),
                'key' => 'status',
                'type' => ColumnTypes::LABEL,
                'label_values' => [
                    PayoutStatus::PENDING => [
                        'text' => trans('Pending'),
                        'class' => 'badge badge-warning',
                    ],
                    PayoutStatus::APPROVED => [
                        'text' => trans('Approve'),
                        'class' => 'badge badge-info',
                    ],
                    PayoutStatus::DISAPPROVE => [
                        'text' => trans('Disapproved'),
                        'class' => 'badge badge-danger',
                    ],
                    PayoutStatus::PAID => [
                        'text' => trans('Paid'),
                        'class' => 'badge badge-success',
                    ]
                ]
            ]

        ];
        $this->searchColumn = ["name"];
        parent::__construct();

        $this->permitted_actions = [
            //TODO: Permissions
            'index' => PayoutPermission::PAYOUT_INDEX,
            'create' => PayoutPermission::PAYOUT_CREATE,
            'edit' => PayoutPermission::PAYOUT_EDIT,
            'show' => PayoutPermission::PAYOUT_INDEX,
            'delete' => PayoutPermission::PAYOUT_DELETE,
        ];
    }

    public function callbackQuery($query)
    {

        if ($this->request->status) {
            $query->where('status', $this->request->status);
        }

        return $query;
    }

    public function index()
    {
        $this->pushBreadcrumb(trans('lang.index'), null, true);

        $this->ifMethodExistCallIt('onIndex');
        $this->request->flash();
        $search = $this->request->search;

        $rows = $this->model;

        if (!is_null($this->orderBy)) {
            $rows = $rows->orderBy($this->orderBy[0], $this->orderBy[1]);
        }

        if (!empty($search)) {
            $rows->where(function ($rows) use ($search) {

                foreach ($this->searchColumn as $key => $column) {
                    if ($key == 0) {
                        $rows = $rows->where($column, "LIKE", "%{$search}%")
                            ->orWhereHas('course', function ($query) use ($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('internal_name', 'like', '%' . $search . '%');
                            });
                    } else {
                        $rows = $rows->orWhere($column, "LIKE", "%{$search}%")
                            ->orWhereHas('course', function ($query) use ($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('internal_name', 'like', '%' . $search . '%');
                            });
                    }
                }
            });
        }

        if (method_exists($this, 'callbackQuery')) {
            $rows = $this->callbackQuery($rows);
        }

        $rows = $rows->paginate($this->perPage);

        if ($this->request->ajax())
            return response()->json($this->view('loop', [
                'rows'           => $rows,
                'select_columns' => $this->select_columns,
            ])->render());

        return $this->view('index', [
            'rows'           => $rows,
            'select_columns' => $this->select_columns,
            'breadcrumb'     => $this->breadcrumb,
        ]);
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            'royalty'                   => ['required', 'numeric', 'between:0,99999999999.99'],
            'royalties_carried_out'     => ['numeric', 'between:0,99999999999.99'],
            'outstanding_advances'      => ['numeric', 'between:0,99999999999.99'],
            "course_id"                 => ["required"],
            'year'                      => ['required_if:type,1', 'max:99999', 'min:1'],
            'quarter'                   => ['required_if:type,1', 'in:1,2,3,4'],
            'start_date'                => ['required_if:type,2', 'date'],
            'end_date'                  => ['required_if:type,2', 'date', 'after:start_date'],

        ], $this->messages());
    }
    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            'royalty'                   => ['required', 'numeric', 'between:0,99999999999.99'],
            'royalties_carried_out'     => ['numeric', 'between:0,99999999999.99'],
            'outstanding_advances'      => ['numeric', 'between:0,99999999999.99'],
            'year'                      => ['required_if:type,1', 'max:99999', 'min:1'],
            'quarter'                   => ['required_if:type,1', 'in:1,2,3,4'],
            'start_date'                => ['required_if:type,2', 'date'],
            'end_date'                  => ['required_if:type,2', 'date', 'after:start_date'],

        ], $this->messages());
    }

    public function messages()
    {
        return [
            'year.required_if' => 'The year field is required when type is quarter.',
            'quarter.required_if' => 'The quarter field is required when type is quarter.',
            'start_date.required_if' => 'The start date field is required when type is date.',
            'end_date.required_if' => 'The end date field is required when type is date.',
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response|View
     */
    public function show($id)
    {
        $this->ifMethodExistCallIt('onShow');
        $main_column = $this->main_column;
        $row = $this->model->findOrFail($id);
        $this->pushBreadcrumb($row->$main_column, null, false);
        $this->ifMethodExistCallIt('isShowed', $row);
        $instructor = $row->instructor;
        $courses =  $instructor->instructor_courses()->get()->pluck('internal_name_or_name', 'id');
        $courses->prepend("Choose form list");
        // dd($courses);
        return $this->view('show', [
            'row'               => $row,
            'courses'           => $courses,
            'royalties'         => $row->royalties,
            'breadcrumb' => $this->breadcrumb,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response|Voild
     */
    public function store()
    {
        $this->ifMethodExistCallIt('onStore');

        if ($this->request->type == PayoutType::QUARTER) {
            $timeframe = $this->getQuarter();
        } else {
            $timeframe['start_date'] = $this->request->start_date;
            $timeframe['end_date'] = $this->request->end_date;
        }


        // dd($this->request->all());
        $start_date = $timeframe['start_date'];
        $end_date = $timeframe['end_date'];

        $instructor_id =  Course::find($this->request->course_id)->user_id;

        if ($this->request->type == PayoutType::QUARTER && $this->model
                ->where(['course_id' => $this->request->course_id, 'quarter' => $this->request->quarter, 'year' => $this->request->year])
                ->where('status', "!=", PayoutStatus::DISAPPROVE)->count() > 0) return response([
            'errors' => [
                'quarter' => ['Already there is a payout for this course in this quarter']
            ]
        ], 422);


        if ($this->request->type == PayoutType::DATE && $this->model
                ->where(['course_id' => $this->request->course_id])
                ->whereBetween('end_date', [$this->request->start_date, $this->request->end_date])
                ->where('status', "!=", PayoutStatus::DISAPPROVE)->count() > 0) return response([
            'errors' => [
                'quarter' => ['Already there is a payout for this course between these dates']
            ]
        ], 422);

        $autoGeneratedId = $this->model->where('user_id', $instructor_id)->count() + 1;
        $name = "Q{$this->request->quarter} {$this->request->year} Payout";
        $period = "{$start_date} - {$end_date}";
        $payout = $this->model->create([
            'name' => $name,
            'period' => $period,
            'status' => PayoutStatus::PENDING,
            'amount' => 0,
            'attachment_id' => null, //TODO: create invoice
            'user_id' => $instructor_id,
            'course_id' => $this->request->course_id,
            'year' => $this->request->year,
            'quarter' => $this->request->quarter,
            'royalty' => $this->request->royalty,
            'royalties_carried_out' => $this->request->royalties_carried_out,
            'outstanding_advances' => $this->request->outstanding_advances,
            'secondary_id' => $autoGeneratedId,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'type' => $this->request->type,
        ]);
        $this->savePDF($payout);

        $this->ifMethodExistCallIt('isStored', true);
        return $this->returnMessage(true, 1, [
            'success' => "You have a new payout created."
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response|Voild
     */
    public function update($id)
    {
        $this->ifMethodExistCallIt('onUpdate');
        $row = $this->model->find($id);
        if ($row->quarter != $this->request->quarter || $row->year != $this->request->year) {
            if ($this->request->type == PayoutType::QUARTER && $this->model->where(['user_id' => $row->course->user_id, 'quarter' => $this->request->quarter, 'year' => $this->request->year])->where('status', "!=", PayoutStatus::DISAPPROVE)->count() > 0) return response([
                'errors' => [
                    'quarter' => ['Already there is a payout for this course in this quarter']
                ]
            ], 422);
        }

        if ($row->start_date != $this->request->start_date || $row->end_date != $this->request->end_date) {
            if ($this->request->type == PayoutType::DATE && $this->model->where(['user_id' => $row->course->user_id])->whereBetween('end_date', [$this->request->start_date, $this->request->end_date])->where('status', "!=", PayoutStatus::DISAPPROVE)->count() > 0) return response([
                'errors' => [
                    'quarter' => ['Already there is a payout for this course between these dates']
                ]
            ], 422);
        }

        if ($this->request->type == PayoutType::QUARTER) {
            $timeframe = $this->getQuarter();
        } else {
            $timeframe['start_date'] = $this->request->start_date;
            $timeframe['end_date'] = $this->request->end_date;
        }


        $start_date = $timeframe['start_date'];
        $end_date = $timeframe['end_date'];
        $name = "Q{$this->request->quarter} {$this->request->year} Payout";
        $period = "{$start_date} - {$end_date}";
        $row->period = $period;
        $row->name = $name;
        $row->year = $this->request->year;
        $row->quarter = $this->request->quarter;
        $row->status = PayoutStatus::PENDING;
        $row->royalty = $this->request->royalty;
        $row->royalties_carried_out = $this->request->royalties_carried_out;
        $row->outstanding_advances = $this->request->outstanding_advances;
        $row->start_date = $start_date;
        $row->end_date = $end_date;
        $row->type = $this->request->type;
        $row->save();

        $this->savePDF($row);

        $this->ifMethodExistCallIt('isUpdated', $row);

        return $this->returnMessage(true, 2);
    }

    private function getQuarter()
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

        $selected_quarter = $quarters[$this->request->quarter];

        $start_date = $selected_quarter['start_date'];
        $end_date = $selected_quarter['end_date'];

        $start_date = str_replace('{{year}}', $this->request->year, $start_date);
        $end_date = str_replace('{{year}}', $this->request->year, $end_date);

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }


    public function approve($id)
    {
        $this->model->find($id)
            ->update([
                'status' => PayoutStatus::APPROVED
            ]);
        return redirect("$this->route")->with('success', 'Payout approved');
    }

    public function disapprove($id)
    {
        $this->model->find($id)
            ->update([
                'status' => PayoutStatus::DISAPPROVE
            ]);
        return redirect("$this->route")->with('success', 'Payout approved');
    }

    public function paid($id)
    {
        $this->model->find($id)
            ->update([
                'status' => PayoutStatus::PAID
            ]);
        return redirect("$this->route")->with('success', 'Payout approved');
    }

    public function getProSubscriptionOnly($watchMins)
    {
        $total_watched_mins = 0;
        foreach ($watchMins as $watchMin) {
            $proSubscription = PackageSubscription::join('user_subscriptions', function ($join) {
                $join->on('user_subscriptions.package_id', '=', 'package_subscriptions.id');
            })->where('user_subscriptions.user_id', $watchMin->user_id)
                ->whereIn('package_subscriptions.type', [SubscriptionPackageType::ANNUAL, SubscriptionPackageType::MONTHLY])
                ->where(function ($query) use ($watchMin) {
                    $query->where('user_subscriptions.created_at', '<=', $watchMin->created_at);
                    $query->where('user_subscriptions.expired_at', '>=', $watchMin->created_at);
                })->count();
            if ($proSubscription > 0)
                $total_watched_mins = $total_watched_mins + $watchMin->watched_time;
        }
        return $total_watched_mins;
    }

    public function onCreate()
    {
        parent::onCreate();

        $this->shareViews();
    }

    private function shareViews()
    {

        $courses =  Course::active()->where("course_type", CourseType::COURSE)->get()->pluck('internal_name_or_name', 'id');
        view()->share(compact('courses'));
    }

    public function export()
    {
        $admin = $this->auth();
        dispatch(new PayoutExcelExportJob($this->request->all(), $admin))->onQueue('payout');
        //         dispatch(new PayoutExcelExportJob($this->request->all(), $admin));
        return \response()->json(['status' => 'true', 'message' => 'Excel sheet will be sent to you mail shortly']);
    }

    public function addRoyalties()
    {
        $row = $this->model->findOrFail($this->request->id);
        $user_id = $row->instructor->id;
        if (count($this->request->courses)  == 1 && $this->request->courses[0] == 0) {
            $this->savePDF($row);
            return $this->returnMessage($row, 2);
        }
        $access_ids = $this->request->courses;
        foreach ($access_ids as $index => $course_id) {
            if (empty($course_id)) {
                continue;
            }
            $data[$course_id] = [
                'outstanding_advances' => $this->request->advances[$index],
                'royalty' =>  $this->request->advances[$index],
                'royalties_carried_out' =>  $this->request->royalties_carried_out[$index],
                'course_id' => $course_id,
                'user_id' =>  $user_id,
                'payout_id' => $row->id,
            ];
            $payout_royalty = new PayoutRoyalty();

            $payout_royalty->outstanding_advances = $this->request->advances[$index];
            $payout_royalty->royalty =  $this->request->royalties[$index];
            $payout_royalty->royalties_carried_out =  $this->request->royalties_carried_out[$index];
            $payout_royalty->course_id = $course_id;
            $payout_royalty->user_id =  $user_id;
            $payout_royalty->payout_id = $row->id;

            $payout_royalty->save();
        }
        // PayoutRoyalty::whereNotIn('course_id', $access_ids)->where('payout_id', $row->id)->delete();
        // $row->royalties()->sync($data);
        return $this->savePDF($row);
        return $this->returnMessage($row, 2);
    }


    public function savePDF(Payout $payout)
    {
        $user = $payout->instructor;
        $course = $payout->course;

        $royalty =  $payout->royalty;
        $advances = $payout->outstanding_advances;
        $royalties_carried_out =  $payout->royalties_carried_out;
        $new_balance = ($royalty - $advances) + (int)$royalties_carried_out;
        $payabal =  $new_balance > 0 ? $new_balance : 0;

        $data = compact('payout', 'user', 'royalty', 'advances', 'course', 'royalties_carried_out', 'payabal');
        $render = view('user::pdf.instructor.invoices.pdf-invoice', $data)->render();
        // return $render;
        // return $render;

        $mpdf = new \Mpdf\Mpdf([
            // 'orientation' => '',
            // 'margin_left'                => 0,
            // 'margin_right'               => 0,
            // 'margin_top'                 => 0,
            // 'margin_bottom'              => 0,
            // 'margin_header'              => 0,
            // 'margin_footer'              => 0,
            'tempDir' =>  sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf'
        ]);
        $mpdf->falseBoldWeight = 1;
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->WriteHTML($render);

        $name = (uniqid() . '.pdf');
        Storage::put($name, $mpdf->Output($name, "S"));
        $file_id = $this->saveToFileManagerDB($name)->id;
        $payout->update(['attachment_id' => $file_id]);
    }

    private function saveToFileManagerDB(string $path): Upload
    {
        return Upload::create([
            "path" => $path,
            "full_url"  => Storage::url($path),
            "size" => 'system-generated',
            "mime_type" => 'image/png',
            'in_use' => 1,
            'container' => config('filesystems.disks.azure.container')
        ]);
    }
    public function exportPDF()
    {
        $row = $this->model->findOrFail($this->request->id);
        return redirect($row->attachment->full_url);
    }

    public function showReport()
    {
        return $this->view('report', [
            'breadcrumb' => $this->breadcrumb,
        ]);
    }

    public function exportExcel()
    {
        ini_set('memory_limit', '-1');
        $quarter = request('quarter');
        $year = request('year');
        $billable_revenue = request('billable_revenue');

        if ($this->request->type == PayoutType::QUARTER) {
            $timeframe = $this->getQuarter($quarter, $year);
        } else {
            $timeframe['start_date'] = $this->request->start_date;
            $timeframe['end_date'] = $this->request->end_date;
        }

        $start_date = $timeframe['start_date'];
        $end_date = $timeframe['end_date'];
        // 1) get Overall Mins from (Pro) in this quarter // 84934.766666667
        $overall_paid_pro_mins = $this->getSubscriptionsMins($start_date, $end_date, [AccessType::PRO]);

        // 2) Overall mins from Active Users in this quarter
        $overall_active_users_mins = $this->getSubscriptionsMins($start_date, $end_date, [AccessType::PRO, AccessType::COURSES, AccessType::COURSE_CATEGORY]);

        $course_ids = WatchHistoryTime::whereBetween('created_at', [$start_date, $end_date])->pluck('course_id')->unique();
        $courses = Course::whereIn('id', $course_ids)->get();
        $payoutArray = [];
        foreach ($courses as $course) {
            // 3) get paid(Pro only) mins in this quarter for this course in this quarter
            $course_paid_pro_mins = $this->getSubscriptionsMins($start_date, $end_date, [AccessType::PRO], $course->id);
            // 4) Overall mins from Active subscriptions (Pro+Bundle)  in this quarter for this course in this quarter
            $course_active_users_mins = $this->getSubscriptionsMins($start_date, $end_date, [AccessType::PRO, AccessType::COURSES, AccessType::COURSE_CATEGORY], $course->id);

            //5) Contribution % (Pro only) for this course in this quarter = Pro Course Mins(#2)/Pro Platform Mins(#1)
            $contribution_percentage = $course_paid_pro_mins / ($overall_paid_pro_mins == 0 ? 1 : $overall_paid_pro_mins);

            //6) Revenue Contribution = Contribution % (Pro only)(#5) * billable revenue
            $revenue_contribution = (float) ($contribution_percentage * $billable_revenue);

            //7) Course Commission
            $course_commission = (float) ((float) $course->commission_percentage / 100);

            //8) Royalty = Revenue Revenue Contribution(#6) * Course Commission(#7)
            $royalty = ($revenue_contribution * $course_commission);

            $instructor = $course->user;
            $instructor_name = 'Not Found';
            if ($instructor) {
                $instructor_name = $instructor->first_name . ' ' . $instructor->last_name;
            }

            $payoutArray[] = [
                'Instructor Name' => $instructor_name,
                'Course Name'     => $course->name,
                'year'            => $year,
                'quarter'         => $quarter,

                'Mins watched from Pro subscriptions (Course)'   => $course_paid_pro_mins,
                'Mins watched from Pro subscriptions (Platform)' => $overall_paid_pro_mins,

                'Mins watched from Active users (Course)'   => $course_active_users_mins,
                'Mins watched from Active users (Platform)' => $overall_active_users_mins,

                'Billable Revenue'                  => $billable_revenue,
                'Revenue Contribution % (Pro only)' => $contribution_percentage,
                'Revenue Contribution (USD)'        => $revenue_contribution,

                'Course Commission' => "$course_commission",
                'Royalties'         => $royalty,
            ];
        }

        $payoutArray = collect($payoutArray);

        $report_name = "{$quarter}-{$year}-" . Carbon::now()->timestamp . '.xlsx';
        $report = new ReceiptExport($payoutArray);

        return Excel::download($report, $report_name);
    }


    function getSubscriptionsMins($start_date, $end_date, array $subscription_type = [AccessType::PRO, AccessType::COURSES, AccessType::COURSE_CATEGORY], string $course_id = null)
    {
        $watched_mins = WatchHistoryTime::whereBetween('created_at', [$start_date, $end_date]);
        if ($course_id) {
            $watched_mins = $watched_mins->where('course_id', $course_id);
        }
        $watched_mins = $watched_mins->where('course_type', CourseType::COURSE)
            ->whereHas('user', function ($query) use ($start_date, $end_date, $subscription_type) {
                $query->whereHas('subscriptions', function ($query) use ($start_date, $end_date, $subscription_type) {
                    // where watch_history_times created_at <= expired_at of subscription
                    $query->whereRaw('watch_history_times.created_at <= user_subscriptions.expired_at')
                        ->whereHas('package', function ($query) use ($subscription_type) {
                            $query->whereIn('package_subscriptions.access_type', $subscription_type);
                        });
                });
            });
        return (float) ($watched_mins->sum('watch_history_times.watched_time') / 60);
    }
}
