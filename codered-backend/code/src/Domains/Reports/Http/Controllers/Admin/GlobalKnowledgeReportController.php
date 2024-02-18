<?php

namespace App\Domains\Reports\Http\Controllers\Admin;

use App\Domains\User\Models\User;
use App\Domains\Reports\Rules\GlobalKnowledgeReportPermission;
use App\Domains\Reports\Exports\GlobalKnowledgeReportExport;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;
use ColumnTypes;
use Constants;
use DB;
use Maatwebsite\Excel\Excel;

class GlobalKnowledgeReportController extends CoreController
{

    use HasAuthorization;

    public $domain = "reports";

     public function __construct(User $model)
    {
         $this->model = $model;
         $this->select_columns = [
           
            [
                'name' => trans("reports::lang.first_name"),
                'key'  => 'first_name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("reports::lang.last_name"),
                'key'  => 'last_name',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("reports::lang.email"),
                'key'  => 'email',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("reports::lang.phone"),
                'key'  => 'phone',
                'type' => ColumnTypes::STRING,
            ],
           
            
        ];
        $this->searchColumn = ["first_name","last_name","email","phone"];
        $this->orderBy = null;
        parent::__construct();

        $this->permitted_actions = [
            'index'  => GlobalKnowledgeReportPermission::KNOWLEDGE_REPORT_INDEX,
        ];


    }


    public function onIndex()
    {
        parent::onIndex();

        $this->shareViews();
    }

    private function shareViews()
    {
       
    }


    public function export(){

        return \Maatwebsite\Excel\Facades\Excel::download(new GlobalKnowledgeReportExport, 'Global Knowledge Report-' . date('M Y') . '.xlsx');
        
    }

   
    public function callbackQuery($query)
    {

        return $query->join('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
                     ->select('users.*', 
                                DB::raw('user_subscriptions.subscription_id ,user_subscriptions.expired_at,user_subscriptions.created_at as subscription_start_date')
                             )
                     ->whereIn('users.id', function($query)
                              {
                                    $query->select('user_id')
                                          ->from('oauth_access_tokens')
                                          ->where('client_id', 9);
                              });
                     

    }
    
}
