<?php

namespace App\Domains\User\Http\Controllers\Api\V1\User\Profile;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\User\Features\Api\V1\User\OnBoarding\SaveOnBoardingQuizFeature;
use App\Domains\User\Features\Api\V1\User\OnBoarding\FetchOnboardingQuizeFeature;

class OnBoardingQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(FetchOnboardingQuizeFeature::class);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->serve(SaveOnBoardingQuizFeature::class);
    }


}
