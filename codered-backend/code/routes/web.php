<?php

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Reports\Exports\LessonQuizesExport;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Export\ReceiptExport;
use Carbon\Carbon;

/**
 * INTCORE IMPORTANT
 * You shouldnt add any routes here
 * please add routes in each domain separated
 *
 * you can add testing routes here but please remove them after testing
 */

/*
use App\Domains\Course\Enum\CourseType;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\LessonMsq;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Reports\Exports\LessonQuizesExport;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Export\ReceiptExport;
use App\Domains\User\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;*/

function getQuarter($quarter, $year)
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

/**
 * @param             $start_date
 * @param             $end_date
 * @param array       $subscription_type
 * @param string|null $course_id
 * @return float
 */
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

Route::get('get-payouts', function () {
    ini_set('memory_limit', '-1');

    $pass = '1332123';

    // check if pass equal pass in request
    if (request()->get('pass') != $pass) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Wrong pass'
        ]);
    }

    $quarter = request('quarter');
    $year = request('year');
    $billable_revenue = request('billable_revenue');

    $timeframe = getQuarter($quarter, $year);

    $start_date = $timeframe['start_date'];
    $end_date = $timeframe['end_date'];
    // 1) get Overall Mins from (Pro) in this quarter // 84934.766666667
    $overall_paid_pro_mins = getSubscriptionsMins($start_date, $end_date, [AccessType::PRO]);

    // 2) Overall mins from Active Users in this quarter
    $overall_active_users_mins = getSubscriptionsMins($start_date, $end_date, [AccessType::PRO, AccessType::COURSES, AccessType::COURSE_CATEGORY]);

    $course_ids = WatchHistoryTime::whereBetween('created_at', [$start_date, $end_date])->pluck('course_id')->unique();
    $courses = Course::whereIn('id', $course_ids)->get();
    $payoutArray = [];
    foreach ($courses as $course) {
        // 3) get paid(Pro only) mins in this quarter for this course in this quarter
        $course_paid_pro_mins = getSubscriptionsMins($start_date, $end_date, [AccessType::PRO], $course->id);
        // 4) Overall mins from Active subscriptions (Pro+Bundle)  in this quarter for this course in this quarter
        $course_active_users_mins = getSubscriptionsMins($start_date, $end_date, [AccessType::PRO, AccessType::COURSES, AccessType::COURSE_CATEGORY], $course->id);

        //5) Contribution % (Pro only) for this course in this quarter = Pro Course Mins(#2)/Pro Platform Mins(#1)
        $contribution_percentage = $course_paid_pro_mins / $overall_paid_pro_mins;

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



});

Route::get('get-quizzes-report', function () {
    ini_set('memory_limit', '-1');

    $pass = '1332123';

    // check if pass equal pass in request
    if (request()->get('pass') != $pass) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Wrong pass'
        ]);
    }

    return Excel::download(new LessonQuizesExport, 'quizes.xlsx');

//
//Route::get('test', function () {
//    App\Domains\User\Models\User::whereHas('subscriptions', function ($query) {
//        $query->active()->whereHas('package', function ($query) {
//            $query->where('type', App\Domains\Payments\Enum\SubscriptionPackageType::MONTHLY);
//        });
//    })->count();
//
//    App\Domains\User\Models\User::active()->count();
//
//    \App\Domains\Course\Models\WatchedLesson::where('created_at', '>=', \Carbon\Carbon::parse('19-03-2021'))->count();
//
//    \App\Domains\Course\Models\LessonUserNote::count();
//
//    App\Domains\Course\Models\CompletedCourses::where('created_at', '>=', \Carbon\Carbon::parse('19-03-2021'))->count();
//
//    App\Domains\User\Models\User::active()->where('created_at', '>=', \Carbon\Carbon::parse('19-03-2021'))->count();
//
//});
//
});



/*Route::get('test', function () {
//  $course = Course::first();
// return generateCourseCertificate($course);

    $learn = \App\Domains\Payments\Models\LearnPathInfo::first();
    return generatePathCertificate($learn);


});


function generatePathCertificate(LearnPathInfo $learn_path)
{

//    $name = ucwords(strtolower($this->user->full_name));
    $name = ucwords(strtolower('$this->user->full_name'));
    $date = date('dS M Y');
//    $number = $this->result->certificate_number;
    $number = '$this->result->certificate_number';
    $course_name = $learn_path->name;

    $image = Image::make(resource_path('certificate-template/learnPath-certificate.png'));
    // set username
    $fontSize = 23;
    // set username
    $image->text($name, 421, 319.79, function ($font) use ($fontSize) {
        $font->color('#252533');
        $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
        $font->size($fontSize);
        $font->align('center');

    });

    // set date
    $image->text($date, 172, 540, function ($font) {
        $font->color('#252533');
        $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
        $font->size(16);
    });

    // set number
    $image->text($number, 600, 540, function ($font) {
        $font->color('#252533');
        $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
        $font->size(16);
        $font->align('center');

    });

    if (strlen($course_name) < 60) {
        $course_name_size = 23;
    } else {
        $course_name_size = 18;

    }
    $image->text($course_name, 421, 410, function ($font) use ($course_name_size) {
        $font->color('#252533');
        $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
        $font->size($course_name_size);
        $font->align('center');
    });
//    $path = "/certificates/{$this->result->id}.png";
    $path = "/certificates/dsddsd.png";
    $certificate_png = $image->stream('png', 100);
    Storage::put($path, $certificate_png->__toString());

    return $path;
}

 function generateCourseCertificate(Course $course)
{

    $name = ucwords(strtolower('$this->user->full_name'));
    $date = date('dS M Y');
    $number =' $this->result->certificate_number';
    $course_name = $course->name;

    $image = Image::make(resource_path('certificate-template/pro-course-certificate.png'));

    // set username
    if(strlen($name) < 39){
        $fontSize = 95.01;
    }
    else{
        $fontSize = 60.01;
    }
    // set username
    $image->text( $name , 950, 640.79, function ($font) use ($fontSize) {
        $font->color('#E02522');
        $font->file(resource_path('certificate-template/GreatVibes-Regular.otf'));
        $font->size($fontSize);
        $font->align('center');

    });

    // set date
    $image->text($date, 1200, 970, function ($font) {
        $font->color('#323133');
        $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
        $font->size(45);
    });

    // set number
    $image->text($number, 450, 990, function ($font) {
        $font->color('#323133');
        $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
        $font->size(45);
    });

    if(strlen($course_name) < 70){
        $course_name_size =  35;
    }
    else{
        $course_name_size =  27;

    }
    $image->text($course_name, 800, 800, function ($font) use ($course_name_size) {
        $font->color('#323133');
        $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
        $font->size($course_name_size);
        $font->align('center');
    });//    $path = "/certificates/{$this->result->id}.png";
     $rand = Carbon::now();
    $path = "/certificates/sdasdwqf.png";
    $certificate_png = $image->stream('png', 100);
   $t = Storage::put($path, $certificate_png->__toString());
dd($path);
    return $t;
}*/
