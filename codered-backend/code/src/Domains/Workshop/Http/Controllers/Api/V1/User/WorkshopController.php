<?php

namespace App\Domains\Workshop\Http\Controllers\Api\V1\User;

use App\Domains\Workshop\Features\Api\V1\User\GetWorkshopByIdFeature;
use App\Domains\Workshop\Features\Api\V1\User\GetWorkshopFeature;
use INTCore\OneARTFoundation\Http\Controller;

class WorkshopController extends Controller
{

    public function index()
    {
        return $this->serve(GetWorkshopFeature::class);
    }

    public function show()
    {
        return $this->serve(GetWorkshopByIdFeature::class);
    }





}
