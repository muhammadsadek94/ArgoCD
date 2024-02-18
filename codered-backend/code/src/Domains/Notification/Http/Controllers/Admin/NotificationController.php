<?php

namespace App\Domains\Notification\Http\Controllers\Admin;

use App\Domains\Geography\Models\Area;
use App\Domains\Geography\Models\City;
use App\Domains\Geography\Models\Country;
use App\Domains\Notification\Notifications\SendUserPushNotificationNotifications;
use App\Domains\User\Models\User;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class NotificationController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(config('app.locale') == 'en'){
            $countries = Country::where("show_country", 1)->active()->pluck("name_en", "id");
            $cities = City::active()->pluck("name_en", "id");
            $areas = Area::active()->pluck("name_en", "id");
        }else{
            $countries = Country::where("show_country", 1)->active()->pluck("name_ar", "id");
            $cities = City::active()->pluck("name_en", "id");
            $areas = Area::active()->pluck("name_en", "id");
        }
        return view("notification::admin.send-notification", compact("countries", "cities", "areas"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
           "title_en" => "required:max:50",
           "title_ar" => "required:max:50",
           "body_en" => "required:max:255",
           "body_ar" => "required:max:255",
        ]);
        $rows = User::active();
        if ($request->has("type")){
            if ($request->get("type") == 9){
                $rows = $rows->where("type", ">", "1");
            }elseif ($request->get("type") == 1){
                $rows = $rows->where("type", "1");
            }
            elseif ($request->get("type") > 1 &&  $request->get("type") < 9){
                $rows = $rows->where("type", $request->get("type"));
            }
        }

        if ($request->get("country") !== null){
            $rows = $rows->where("country_id", $request->get("country"));
        }

        if ($request->get("city") !== null){
            $rows = $rows->where("city_id", $request->get("city"));
        }

        if ($request->get("area") !== null){
            $rows = $rows->where("area_id", $request->get("area"));
        }

        if ($request->get("gender") !== null){
            $rows = $rows->where("gender", $request->get("gender"));
        }

        $rows = $rows->get();
        if (count($rows) == 0){
            return redirect()->back()->withErrors(trans("notification::lang.sorry_this_criteria_is_not_exist"));
        }
        foreach ($rows as $row){
            $row->notify(new SendUserPushNotificationNotifications($request));
        }
        return redirect()->back()->withSuccess(trans("notification::lang.sent_successfully"));
    }
}
