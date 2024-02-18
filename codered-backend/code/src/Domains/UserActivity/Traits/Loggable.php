<?php

namespace App\Domains\UserActivity\Traits;

use App\Domains\UserActivity\Models\UserActivityLog;
use Illuminate\Support\Facades\DB;

trait Loggable
{
    static function logToDb($model, $logType)
    {
        if (!auth()->guard('admin')->check()) return ;

        $data = $model->wasRecentlyCreated ? $model->toArray() : $model->getOriginal();

        $originalData = json_encode($data);

        $tableName = $model->getTable();
        $userId = auth()->guard('admin')->user()->id;

        UserActivityLog::create([
            'user_id'    => $userId,
            'table_name' => $tableName,
            'log_type'   => $logType,
            'data'       => $originalData
        ]);
    }

    public static function boot()
    {
        parent::boot();

        if (config('user-activity.log_events.on_create', false)) {
            self::created(function ($model) {
                self::logToDb($model, 'create');
            });
        }

        if (config('user-activity.log_events.on_edit', false)) {

            self::updated(function ($model) {
                self::logToDb($model, 'edit');
            });
        }

        if (config('user-activity.log_events.on_delete', false)) {
            self::deleted(function ($model) {
                self::logToDb($model, 'delete');
            });
        }




    }
}
