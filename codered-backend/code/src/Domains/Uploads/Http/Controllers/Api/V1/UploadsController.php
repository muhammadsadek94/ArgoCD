<?php

namespace App\Domains\Uploads\Http\Controllers\Api\V1;

use App\Domains\Uploads\Features\UploadFileFeature;
use App\Domains\Uploads\Features\UploadFilePrivateFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class UploadsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->serve(UploadFileFeature::class);
    }

    public function storePrivate()
    {
        return $this->serve(UploadFilePrivateFeature::class);
    }

}
