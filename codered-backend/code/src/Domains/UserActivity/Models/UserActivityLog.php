<?php namespace App\Domains\UserActivity\Models;

use App\Domains\Admin\Models\Admin;
use INTCore\OneARTFoundation\Model;

/**
 * @property mixed created_at
 * @property mixed dateHumanize
 * @property mixed json_data
 */
class UserActivityLog extends Model
{
    protected $table = 'user_activity_logs';
    protected $fillable = ['user_id', 'table_name', 'log_type', 'data'];
    protected $appends = ['dateHumanize', 'json_data', 'created_at_formatted'];

    public function getDateHumanizeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }


    public function getJsonDataAttribute()
    {
        return json_decode($this->data, true);
    }

    public function user()
    {
        return $this->belongsTo(Admin::class);
    }
}

