<?php

namespace App\Domains\Geography\Http\Controllers\Api\V2;

use App\Domains\Geography\Features\Api\V2\GetCitiesAreasFeature;
use App\Domains\Geography\Features\Api\V2\GetCountryCitiesFeature;
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
