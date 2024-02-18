<?php

namespace App\Domains\Geography\Http\Controllers\Api\V1;

use App\Domains\Geography\Features\Api\V1\GetCitiesAreasFeature;
use App\Domains\Geography\Features\Api\V1\GetCountryCitiesFeature;
use App\Domains\Geography\Jobs\GetCountryCitiesJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class GeographyControlerController extends Controller
{
    public function getCities(Request $request)
    {
        return $this->serve(GetCountryCitiesFeature::class);
    }

    public function getAreas(Request $request)
    {
        return $this->serve(GetCitiesAreasFeature::class);
    }
}
