<?php

namespace App\Domains\Geography\Features\Api\V1;

use App\Domains\Geography\Jobs\Api\GetCitiesAreasJob;
use App\Domains\Geography\Models\Area;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class GetCitiesAreasFeature extends Feature
{
    public function handle(Request $request)
    {
        $areas = $this->run(GetCitiesAreasJob::class, [
            "request" => $request
        ]);

        return response()->json(["areas" =>  $areas]);
    }
}
