<?php

namespace App\Domains\Payments\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Payments\Features\Api\V1\User\GetSubscriptionPackagesFeature;

class PackageSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getPackages()
    {
        return $this->serve(GetSubscriptionPackagesFeature::class);
    }

}
