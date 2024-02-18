<?php

namespace App\Domains\Uploads\Enum;

use App\Foundation\BasicEnum;

class UploadType extends BasicEnum
{
    const FILES = 'files';
    const VIDEO_LESSON = 'videos_lessons';
    const PRIVATE = 'private';
}
