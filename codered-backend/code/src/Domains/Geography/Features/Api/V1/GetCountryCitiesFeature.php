<?php

namespace App\Domains\Geography\Features\Api\V1;

use App\Domains\Geography\Jobs\Api\GetCountryCitiesJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class GetCountryCitiesFeature extends Feature
{
    public function handle(Request $request)
    {
        $cities = $this->run(GetCountryCitiesJob::class,[
            "request" =>  $request
        ]);

        return response()->json([
            "cities" => $cities
        ]);

    }
}
