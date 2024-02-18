<?php

namespace App\Domains\UserActivity\Http\Controllers;

use App\Domains\UserActivity\Rules\UserActivityPermission;
use App\Foundation\Traits\HasAuthorization;
use Framework\Http\Controllers\Controller;
use App\Domains\Admin\Models\Admin;
use App\Domains\UserActivity\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{

    use HasAuthorization;

    public function __construct()
    {
    }

    private function handleData(Request $request)
    {
        $this->validate($request, [
            'action'    => 'required|string',
            'user_id'   => 'sometimes',
            'log_type'  => 'sometimes|string',
            'table'     => 'sometimes|string',
            'from_date' => 'sometimes|date_format:Y-m-d',
            'to_date'   => 'sometimes|date_format:Y-m-d'
        ]);

        $data = UserActivityLog::with('user');
        if ($request->has('user_id')) {
            $data = $data->where('user_id', request('user_id'));
        }
        if ($request->has('log_type')) {
            $data = $data->where('log_type', request('log_type'));
        }
        if ($request->has('table')) {
            $data = $data->where('table_name', request('table'));
        }
        if ($request->has('from_date') && $request->has('to_date')) {
            $from = request('from_date') . " 00:00:00";
            $to = request('to_date') . " 23:59:59";
            $data = $data->whereBetween('created_at', [$from, $to]);
        }

        return $data->latest('created_at')->paginate(10);
    }

    private function handleCurrentData(Request $request)
    {
        $this->validate($request, [
            'table'  => 'required|string',
            //            'id'     => 'required',
            'log_id' => 'required'
        ]);

        $table = request('table');
        $id = request('id');
        $logId = request('log_id');
        $currentData = DB::table($table)->find($id);
        if ($currentData) {
            $editHistory = UserActivityLog::with('user')
                ->whereNotIn('id', [$logId])
                ->where(['table_name' => $table, 'log_type' => 'edit'])
                ->whereRaw('data like ?', ['%"id":"' . $id . '"%'])
                ->latest('created_at')
                ->get();
            return ['current_data' => $currentData, 'edit_history' => $editHistory];
        }
        return [];
    }

    private function handleUserAutocomplete(Request $request)
    {
        $this->validate($request, [
            'user' => 'required|string|max:50'
        ]);

        $user = request('user');
        return Admin::select('id', 'name', 'email')
            ->where('name', 'like', '%' . $user . '%')
            ->orWhere('id', $user)
            ->limit(10)->get();
    }

    public function getIndex(Request $request)
    {
        $this->hasPermission(UserActivityPermission::USER_ACTIVITY_INDEX);

        if ($request->has('action')) {
            $action = $request->get('action');
            switch ($action) {
                case 'data':
                    return response()->json($this->handleData($request));
                    break;

                case 'current_data':
                    return response()->json($this->handleCurrentData($request));
                    break;

                case 'user_autocomplete':
                    return response()->json($this->handleUserAutocomplete($request));
                    break;
            }
        }

        $all = array_map(function($v) { return reset($v); }, DB::select('SHOW TABLES'));
        $exclude = ['failed_jobs', 'password_resets', 'migrations', 'user_activity_logs'];
        $tables = array_diff($all, $exclude);

        return view('UserActivity::index', ['tables' => $tables]);


    }

    public function handlePostRequest(Request $request)
    {
        $this->hasPermission(UserActivityPermission::USER_ACTIVITY_INDEX);

        if ($request->has('action')) {
            $action = $request->get('action');
            switch ($action) {
                case 'delete':
                    $dayLimit = config('user-activity.delete_limit');
                    UserActivityLog::whereRaw('created_at < NOW() - INTERVAL ? DAY', [$dayLimit])->delete();
                    return ['success' => true, 'message' => "Successfully deleted log data older than $dayLimit days"];
                    break;
            }
        }
    }
}
