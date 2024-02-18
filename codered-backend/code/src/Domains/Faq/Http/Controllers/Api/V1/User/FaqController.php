<?php

namespace App\Domains\Faq\Http\Controllers\Api\V1\User;

use App\Domains\Faq\Features\Api\V1\User\FaqFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(FaqFeature::class);
    }
}
