<?php

namespace App\Domains\ContactUs\Http\Controllers\Api\V1\User;

use App\Domains\ContactUs\Features\Api\V1\GetSubjectsFeature;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\ContactUs\Features\Api\V1\User\SaveFeature;

class ContactUsController extends Controller
{

    public function getSubjects()
    {
        return $this->serve(GetSubjectsFeature::class);
    }


    public function store()
    {
        return $this->serve(SaveFeature::class);
    }
}
