<?php
namespace App\Domains\Course\Http\Controllers\Api\V2\User;

use App\Domains\Course\Features\Api\V2\User\GetCyberQFeature;
use INTCore\OneARTFoundation\Http\Controller;

class CyberQController extends Controller {
   
    public function getCyberqDetails() {
        return $this->serve(GetCyberQFeature::class);
    }

}
