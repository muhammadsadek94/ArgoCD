<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Uploads\Http\Requests\UploadFileRequest;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\Uploads\Jobs\UploadFileJob;


class UploadDefaultImagesFeature extends Feature
{

    public function handle(UploadFileRequest $request)
    {

        $extension = $request->file->getClientOriginalExtension();

        if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png' || $extension == 'webp' || $extension == 'gif' || $extension == 'svg') {
            $request->validate([
                'file' => 'image|max:1024'
            ]);
        }

        $file = $this->run(UploadFileJob::class, [
            'file' => $request->file('file'),
            'is_default_profile_image' => true
        ]);

        return $this->run(new RespondWithJsonJob(new FileResource($file)));
    }
}
