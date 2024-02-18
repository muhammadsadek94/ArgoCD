<?php

namespace App\Domains\Configuration\Features\Api;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

/**
 * @deprecated
 * Class GetConfigurationsFeature
 * @package App\Domains\Configuration\Features\Api
 */
class GetConfigurationsFeature extends Feature
{
    public function handle(Request $request)
    {


        return $this->run(RespondWithJsonJob::class, [
            'content' => [

            ]
        ]);
    }
}
