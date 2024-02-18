<?php

namespace App\Domains\Bundles\Http\Controllers\Api\V1\User;

use App\Domains\Bundles\Features\Api\V1\User\GetBundleByIdFeature;
use App\Domains\Bundles\Features\Api\V1\User\GetHomeBundleFeature;
use INTCore\OneARTFoundation\Http\Controller;

class BundlesController extends Controller
{

    public function index()
    {
        return $this->serve(GetHomeBundleFeature::class);
    }

    public function show()
    {
        return $this->serve(GetBundleByIdFeature::class);
    }





}
