<?php

namespace App\Domains\User\Http\Controllers\Api\V1\Instructor;

use App\Domains\User\Features\Api\V1\Instructor\PayoutsFeature;
use App\Domains\User\Features\Api\V1\Instructor\DashboardFeature;
use INTCore\OneARTFoundation\Http\Controller;

class DashboardController extends Controller
{


    /**
     * authorize user to system.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(DashboardFeature::class);
    }

    public function payouts()
    {
        return $this->serve(PayoutsFeature::class);
    }







}
