<?php

namespace App\Domains\User\Http\Controllers\Api\V2\User\Profile;

use App\Domains\User\Features\Api\V2\User\Profile\CheckDisplayNameFeature;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\User\Features\Api\V2\User\Profile\ProUserDashboardV2Feature;
use App\Domains\User\Features\Api\V2\User\Profile\UpdateDisplayNameFeature;
use Request;

class ProfileV2Controller extends Controller
{
    public function getProUserDashboard()
    {
        // return $this->serve(ProUserDashboardV2Feature::class);
    }

    public function checkDisplayName()
    {
        return $this->serve(CheckDisplayNameFeature::class);
    }

    public function updateDisplayName()
    {
        return $this->serve(UpdateDisplayNameFeature::class);

    }
}
