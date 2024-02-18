<?php


namespace App\Domains\Cms\Http\Controllers\Api\V1;

use App\Domains\Cms\Features\Api\V1\GetSlidersFeature;
use INTCore\OneARTFoundation\Http\Controller;

class SliderController extends Controller {
    public function index() {
        return $this->serve(GetSlidersFeature::class);
    }
}

