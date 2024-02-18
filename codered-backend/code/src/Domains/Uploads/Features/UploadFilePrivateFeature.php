<?php

namespace App\Domains\Uploads\Features;

use App\Domains\Uploads\Enum\UploadType;
use App\Domains\Uploads\Http\Requests\UploadFileRequest;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\Uploads\Jobs\UploadFileJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class UploadFilePrivateFeature extends Feature
{
    public function handle(UploadFileRequest $request)
    {
        $file = $this->run(UploadFileJob::class, [
            'file'      => $request->file('file'),
            'directory' =>'private',
            'type'      =>UploadType::PRIVATE
        ]);

        return $this->run(new RespondWithJsonJob(new FileResource($file)));
    }
}
