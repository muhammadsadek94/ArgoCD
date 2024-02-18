<?php

namespace App\Domains\Course\Models;

use App\Domains\Course\Enum\VideoType;
use App\Domains\Uploads\Enum\UploadType;
use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;
use App\Domains\Uploads\Models\Upload;
use App\Domains\Course\Enum\BrightCove;
use App\Domains\Uploads\Jobs\UploadFileJob;
use Illuminate\Database\Eloquent\Builder;
use App\Domains\Comment\Models\Comment;
use App\Domains\Favourite\Models\Favourite;

/**
 * @property mixed name
 * @property mixed overview
 * @property mixed id
 */
class Lesson extends Model
{
    use Loggable;

    protected $fillable = [
        'name', 'course_id', 'chapter_id', 'type', 'overview', 'video', 'time',
        'activation', 'sort', 'ilab_id', 'image_id', 'cyperq_id', 'manual_id', 'is_free', 'book_id', 'page_number', 'after_chapter',
        'outer_overview'
    ];

    protected $casts = [
        'video' => 'json',
        'time' => 'integer'
    ];

    protected $attributes = [
        'image_id' => null
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            return $builder->orderBy('sort', 'asc');
        });
    }

    public function setVideoAttribute(array|null $data)
    {
        if ($data == null) return;
        $json = $this->video;
        if (array_key_exists('file', $data)) {
            $tmp_file = $data['file'];
            $file = dispatch_now(new UploadFileJob($tmp_file, 'lessons/videos', UploadType::VIDEO_LESSON));
            $json['video_file'] = $file->path;
            $json['upload_id'] = $file->id;
        }

        if (array_key_exists('video_id', $data)) {
            $json['video_id'] = $data['video_id'];
        }
        if (array_key_exists('type', $data)) {
            $json['type'] = $data['type'];
        }

        if (array_key_exists('brightcove_ref_id', $data)) {
            $json['brightcove_ref_id'] = $data['video_id'];
        }
        if (isset($json['type']) && $json['type'] == VideoType::BRIGHTCOVE) {
            $json['account_id'] = BrightCove::ACCOUNTID();
            $json['player_id'] = BrightCove::PLAYERID();
        }

        $this->attributes['video'] = json_encode($json);
    }


    public function setImageIdAttribute(?string $image)
    {
        if (empty($image)) {
            $this->attributes['image_id'] = null;
        } else {
            $this->attributes['image_id'] = $image;
        }
    }

    public function getVideoAttribute(?string $data)
    {

        if ($data != null && $data != 'null') {

            $data = (array)json_decode($data);
            if (!array_key_exists('type', $data) && array_key_exists('video_id', $data)) {
                $data['type'] = \App\Domains\Course\Enum\VideoType::BRIGHTCOVE;
            }
            return (array) $data;
        }
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function resources()
    {
        return $this->hasMany(LessonResource::class, 'lesson_id', 'id');
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourable');
    }

    public function mcq()
    {
        return $this->hasMany(LessonMsq::class);
    }

    public function faq()
    {
        return $this->hasMany(LessonFaq::class);
    }

    public function notes()
    {
        return $this->hasMany(LessonUserNote::class);
    }

    public function voucher()
    {
        return $this->hasMany(LessonVoucher::class, 'lesson_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }

    public function image()
    {
        return $this->belongsTo(Upload::class, 'image_id', 'id');
    }

    public function manual()
    {
        return $this->belongsTo(Upload::class, 'manual_id', 'id');
    }

    public function lesson_objectives()
    {
        return $this->hasMany(LessonObjective::class);
    }

    public function lesson_tasks()
    {
        return $this->hasMany(LessonTask::class);
    }

    public function project()
    {
        return $this->hasOne(ProjectApplication::class);
    }
}
