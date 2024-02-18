<?php

namespace App\Domains\Configuration\Http\Controllers\Api;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Configuration\Features\Api\GetConfigurationsFeature;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(GetConfigurationsFeature::class);
    }

   

}
