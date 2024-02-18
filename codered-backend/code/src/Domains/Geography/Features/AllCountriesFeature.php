<?php

namespace App\Domains\Geography\Features;

use App\Domains\Geography\Jobs\AllCountriesJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\Geography\Http\Resources\CountryResource;

class AllCountriesFeature extends Feature
{
    public function handle(Request $request)
    {
        $countries = $this->run(new AllCountriesJob($request));

        $request->merge(['load_cities' => true]);

        return $this->run(new RespondWithJsonJob(
            CountryResource::collection($countries)
        ));
    }
}
