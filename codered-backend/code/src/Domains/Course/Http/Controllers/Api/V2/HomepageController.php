<?php

namespace App\Domains\Course\Http\Controllers\Api\V2;

use App\Domains\Course\Features\Api\V2\HomepageFeature;
use App\Domains\Course\Features\Api\V2\MenuFeature;
use INTCore\OneARTFoundation\Http\Controller;

class HomepageController extends Controller
{
    public function index()
    {

        return $this->serve(HomepageFeature::class);
    }

    public function menu()
    {
        return $this->serve(MenuFeature::class);
    }
}
