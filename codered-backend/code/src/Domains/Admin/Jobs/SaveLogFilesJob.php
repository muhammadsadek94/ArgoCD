<?php

namespace App\Domains\Admin\Jobs;

use App\Domains\Uploads\Jobs\UploadFileJob;
use App\Domains\Uploads\Models\Upload;
use App\Foundation\Repositories\Repository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use INTCore\OneARTFoundation\Job;
use finfo;

class SaveLogFilesJob extends Job
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $files = scandir(storage_path().'/logs/');
        foreach ($files as $file){
            if (str_contains($file, 'laravel-') || str_contains($file, '.log')){
                $uploaded_file = $this->getAsFile($file);
                $this->uploadFile($uploaded_file);

            }
        }
    }

    /**
     * @param $file
     * @return UploadedFile
     */
    protected function getAsFile($file_name): UploadedFile
    {
        $file_path = storage_path().'/logs/'.$file_name;
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        return new UploadedFile($file_path, $file_name,  $finfo->file($file_path), filesize($file_path));
    }

    private function uploadFile(UploadedFile $file): void
    {
        $directory ='logs/'. env('APP_ENV');
        $repo = new Repository(new Upload);
        $size = $file->getSize();
        $mime_type = $file->getMimeType();
        config()->set('filesystems.disks.azure.name', config('filesystems.disks.azure.log_container_name'));
        config()->set('filesystems.disks.azure.container', config('filesystems.disks.azure.log_container_container'));
        config()->set('filesystems.disks.azure.key', config('filesystems.disks.azure.log_container_key'));
        config()->set('filesystems.disks.azure.endpoint', config('filesystems.disks.azure.log_container_endpoint'));
        config()->set('filesystems.disks.azure.url', config('filesystems.disks.azure.log_container_url'));
        $path = $file->storeAs($directory, $file->getClientOriginalName());

        $file = $repo->fillAndSave([
            "path" => $path,
            "full_url" => Storage::url($path),
            "size" => $size,
            "mime_type" => $mime_type,
            'container' => config('filesystems.disks.azure.log_container_name')
        ]);
    }
}
