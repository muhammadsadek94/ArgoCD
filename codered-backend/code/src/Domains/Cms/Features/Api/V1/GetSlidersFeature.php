<?php

namespace App\Domains\Cms\Features\Api\V1;

use App\Domains\Cms\Http\Resources\Api\V1\SliderResource;
use App\Domains\Cms\Models\Slider;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class GetSlidersFeature extends Feature {
    public function handle() {
        $sliders = Slider::get();

        return $this->run(RespondWithJsonJob::class, [
            "content" => SliderResource::collection($sliders)
        ]);
    }
}
