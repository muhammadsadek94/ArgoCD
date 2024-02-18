<?php

namespace App\Domains\Uploads\Features;

use App\Domains\Uploads\Http\Requests\UploadFileRequest;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\Uploads\Jobs\UploadFileJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class UploadFileFeature extends Feature
{
    public function handle(UploadFileRequest $request)
    {

        $extension = $request->file->getClientOriginalExtension();

        if($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png' || $extension == 'webp' || $extension == 'gif' || $extension == 'svg'){
            $request->validate([
                'file' => 'image|max:1024'
            ]);
        }
        
        $file = $this->run(UploadFileJob::class, [
            'file' => $request->file('file')
        ]);

        return $this->run(new RespondWithJsonJob(new FileResource($file)));
    }
}
