<?php

namespace App\Domains\Bundles\Http\Controllers\Api\V2\User;

use App\Domains\Bundles\Features\Api\V2\User\GetBundleByIdFeature;
use INTCore\OneARTFoundation\Http\Controller;

class BundlesController extends Controller
{

    public function show($bundle_id)
    {
        return $this->serve(GetBundleByIdFeature::class);
    }

}
