<?php

namespace App\Domains\Blog\Http\Controllers\Api\V1\User;

use App\Domains\Blog\Features\Api\V1\User\GetHomeFeature;
use App\Domains\Blog\Features\Api\V1\User\GetSingleArticleFeature;
use App\Domains\Blog\Features\Api\V1\User\RequestDownloadAttachmentFeature;
use App\Domains\Blog\Features\Api\V1\User\SaveArticleLikesFeature;
use INTCore\OneARTFoundation\Http\Controller;

class BlogController extends Controller
{

    public function index()
    {
        return $this->serve(GetHomeFeature::class);
    }

    public function show()
    {
        return $this->serve(GetSingleArticleFeature::class);
    }

    public function storeLikes()
    {
        return $this->serve(SaveArticleLikesFeature::class);
    }

    public function requestDownloadAttachment()
    {
        return $this->serve(RequestDownloadAttachmentFeature::class);
    }

}
