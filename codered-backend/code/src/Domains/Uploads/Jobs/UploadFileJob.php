<?php

namespace App\Domains\Uploads\Jobs;

use App\Domains\Uploads\Enum\UploadType;
use App\Domains\Uploads\Models\Upload;
use App\Foundation\Repositories\Repository;
use File;
use Illuminate\Support\Facades\Storage;
use Image;
use INTCore\OneARTFoundation\Job;
use Log;
use Str;

class UploadFileJob extends Job
{


    protected $file;
    private $directory;
    private $type;
    private $is_default_profile_image;

    /**
     * Create a new job instance.
     *
     * @param      $file
     * @param null $directory
     * @param      $type
     */
    public function __construct($file, $directory = null, $type = UploadType::FILES, $is_default_profile_image = false)
    {
        $this->file = $file;
        $this->directory = $directory;
        $this->type = $type;
        $this->is_default_profile_image = $is_default_profile_image;
    }

    /**
     * Execute the job.
     *
     * @return \INTCore\OneARTFoundation\Model
     */
    public function handle()
    {

        if ($this->type == UploadType::VIDEO_LESSON || $this->type == UploadType::PRIVATE) {
            config()->set('filesystems.disks.azure.container', config('filesystems.disks.azure.container_videos'));
        }
        $repo = new Repository(new Upload);
        $file = $this->file;

        $size = $file->getSize();
        $mime_type = $file->getMimeType();

        if (str_contains($mime_type, 'jpeg') || str_contains($mime_type, 'jpg')) {
            $path = realpath($file);
            $img = imagecreatefromjpeg($path);
            imagejpeg($img, $path, 100);
            imagedestroy($img);
        }


        if ($this->directory == null) {
            $path = $file->store("uploads");
        } else {
            $path = $file->store("uploads/" . $this->directory);
        }

        if (str_contains($mime_type, 'jpeg') || str_contains($mime_type, 'jpg') || str_contains($mime_type, 'png') || str_contains($mime_type, 'svg')) {

            $webpImage = Image::make($file)->encode('webp', 70);

            $path = "/uploads/" . Str::uuid() . ".webp";

            $added_to_storage = Storage::put($path, $webpImage->__toString());
        }

        $full_url = str_replace('coderedcdn.blob.core.windows.net/', 'coderedcdn.eccouncil.org/', Storage::url($path));

        return $repo->fillAndSave([
            'is_default_profile_image' => $this->is_default_profile_image,
            "path" => $path,
            "full_url" => $full_url,
            "size" => $size,
            "mime_type" => $mime_type,
            'container' => config('filesystems.disks.azure.container')
        ]);
    }
}
