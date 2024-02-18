<?php

namespace App\Domains\Reports\Exports;

use App\Domains\User\Models\User;

use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GlobalKnowledgeReportExport implements \Maatwebsite\Excel\Concerns\FromQuery,WithMapping, WithHeadings
{
   

     /**
     * @var array
     */
    //private $user_details;

    use Exportable;

    public function query()
    {
         return User::query()->join('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
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

    
    public function headings(): array
    {
        return [
                'id','first_name','last_name', 'password', 'email' ,'phone','activation','type','image_id','password_reset_code','temp_email_code','temp_phone_code','social_type','social_id','language','created_at','updated_at','country_id','city_id','gender','birth_date','level_experience','daily_target','active_campaign_id','oauth2_client_id','old_db_id','source','subscription_id','expired_at','subscription_start_date'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->first_name,
            $row->last_name,
            $row->password,
            $row->email,
            $row->phone,
            $row->activation,
            $row->type,
            $row->image_id,
            $row->password_reset_code,
            $row->temp_email_code,
            $row->temp_phone_code,
            $row->social_type,
            $row->social_id,
            $row->language,
            $row->created_at,
            $row->updated_at,
            $row->country_id,
            $row->city_id,
            $row->gender,
            $row->birth_date,
            $row->level_experience,
            $row->daily_target,
            $row->active_campaign_id,
            $row->oauth2_client_id,
            $row->old_db_id,
            $row->source,
            $row->subscription_id,
            $row->expired_at,
            $row->subscription_start_date
        ];
    }
    

   
}