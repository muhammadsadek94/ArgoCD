<?php

namespace App\Domains\Geography\Http\Controllers\Api\V2;

use App\Domains\Geography\Features\Api\V2\GetAllAreasFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class AreasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(GetAllAreasFeature::class);
    }
}
