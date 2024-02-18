<?php

namespace App\Domains\Geography\Features\Api\V2;

use App\Domains\Geography\Http\Resources\AreaCollection;
use App\Domains\Geography\Jobs\Api\GetAllAreasJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class GetAllAreasFeature extends Feature
{
    public function handle(Request $request)
    {
        $areas = $this->run(GetAllAreasJob::class);

        return $this->run(RespondWithJsonJob::class, [
            'content' => new AreaCollection($areas)
        ]);
    }
}
