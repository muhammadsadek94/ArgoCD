<?php

namespace App\Domains\Course\Models;

use App\Domains\Uploads\Models\Upload;
use App\Domains\Uploads\Jobs\UploadFileJob;

class LessonResource extends \Eloquent
{
    protected $table = 'lesson_upload';
    protected $fillable = ['name', 'lesson_id', 'attachment_id', 'link', 'type'];
    protected $with = ['attachment'];

    public function attachment()
    {
        return $this->belongsTo(Upload::class, 'attachment_id', 'id');
    }

    public function setAttachmentIdAttribute($file)
    {
        $file = dispatch_now(new UploadFileJob($file, 'lessons/resources'));
        $this->attributes['attachment_id'] = $file->id;
    }

}
