<?php

namespace App\Domains\Reports\Http\Controllers\Admin;

use App\Domains\User\Models\User;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\User\Enum\UserActivation;
use App\Domains\Reports\Rules\SummaryReportPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;
use DB;

class ReportController extends CoreController
{

    use HasAuthorization;

    public $domain = "reports";

    public function __construct()
    {
        
    }




    public function getReports()
    {
        
        if (!empty($_REQUEST['from_date']) && !empty($_REQUEST['to_date']) ){

                $from = $_REQUEST['from_date'] . " 00:00:00";
                $to   = $_REQUEST['to_date'] . " 23:59:59";
        }


        //users who have subscription of no kind 
        if (!empty($_REQUEST['from_date']) && !empty($_REQUEST['to_date'])){

            $free_users_count =User::whereBetween('created_at', [$from, $to])->doesntHave('subscriptions')->count();

        }else{

             $free_users_count = User::doesntHave('subscriptions')->count();
        }

       
     

        //Users who have active monthly plans of any kind
        $monthly_users_count = User::whereHas('subscriptions', function ($query) {
                    $query->active()->whereHas('package', function ($query) {
                        $query->where('type',SubscriptionPackageType::MONTHLY);
                    });
                })->count();


        //Users who have active annual plans of any kind (including vouchers)
        $annual_users_count = User::whereHas('subscriptions', function ($query) {
                    $query->active()->whereHas('package', function ($query) {
                    $query->where('type', SubscriptionPackageType::ANNUAL);
                });
            })->count();


        //Total users who set  goals since the launch
        if (!empty($_REQUEST['from_date']) && !empty($_REQUEST['to_date'])){

             $goal_user_result = DB::table('goal_user')
                 ->select(DB::raw('count(DISTINCT user_id) as total'))
                 ->whereBetween('created_at', [$from, $to])
                 ->first();

        }else{

            $goal_user_result = DB::table('goal_user')
                 ->select(DB::raw('count(DISTINCT user_id) as total'))
                 ->where('created_at', '>=', '2021-03-18 00:00:00' )
                 ->first();
        }
        
        $goal_user_count = $goal_user_result->total;


        //Total user who set notes since the launch 
        if (!empty($_REQUEST['from_date']) && !empty($_REQUEST['to_date'])){

             $lesson_user_count = DB::table('lesson_user_notes')
                           ->whereBetween('created_at', [$from, $to])
                           ->count();
        }else{

            $lesson_user_count = DB::table('lesson_user_notes')
                           ->where('created_at', '>=', '2021-03-18 00:00:00' )
                           ->count();
        }
        


        $users_reset_psswd = DB::table('users')
                            ->where([
                                ['created_at', '>=','2021-03-18 00:00:00'],
                                ['activation',UserActivation::COMPLETE_PROFILE],
                             ])
                           ->count();


        if (!empty($_REQUEST['from_date']) && !empty($_REQUEST['to_date'])){

            $certificates = DB::table('completed_courses')
                               ->whereBetween('created_at', [$from, $to])
                               ->count();

        }else{

            $certificates = DB::table('completed_courses')
                                ->where([
                                    ['created_at', '>=','2021-03-18 00:00:00'],
                                 ])
                               ->count();
        }


        //Total users registered on website since the launch
        if (!empty($_REQUEST['from_date']) && !empty($_REQUEST['to_date'])){

            $total_users_registered = DB::table('users')
                                    ->whereBetween('created_at', [$from, $to])
                                    ->count();
        }else{

             $total_users_registered = DB::table('users')
                                       ->where([
                                            ['created_at', '>=','2021-03-18 00:00:00'],
                                         ])
                                       ->count();

        }
        

        //Total lessons watched since the launch
        if (!empty($_REQUEST['from_date']) && !empty($_REQUEST['to_date'])){

            $total_watched_lessons = DB::table('watched_lessons')
                                ->select(DB::raw('count(DISTINCT lesson_id) as total'))
                                ->whereBetween('created_at', [$from, $to])
                                ->first();
        }else{
            
            $total_watched_lessons = DB::table('watched_lessons')
                                ->select(DB::raw('count(DISTINCT lesson_id) as total'))
                                ->where([
                                    ['created_at', '>=','2021-03-18 00:00:00'],
                                 ])
                               ->first();
        }

        $count_watched_lessons = $total_watched_lessons->total;
        
     

        return view('reports::admin.summary-report.index', compact('free_users_count','monthly_users_count','annual_users_count','goal_user_count','lesson_user_count','users_reset_psswd','certificates','total_users_registered','count_watched_lessons'));
    }
}
