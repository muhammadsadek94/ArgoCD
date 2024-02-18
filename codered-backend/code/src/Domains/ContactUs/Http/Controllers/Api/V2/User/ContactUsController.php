<?php

namespace App\Domains\ContactUs\Http\Controllers\Api\V2\User;

use App\Domains\ContactUs\Features\Api\V2\GetSubjectsFeature;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\ContactUs\Features\Api\V2\User\SaveFeature;

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
