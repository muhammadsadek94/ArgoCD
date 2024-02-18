<?php

namespace App\Domains\Payments\Http\Controllers\Api\V2\LearnPath;

use App\Domains\Enterprise\Features\Api\V1\LearnPath\GetFilterLearnPathsFeature;
use App\Domains\Payments\Features\Api\V2\LearnPath\GetLearnPathByIdFeature;
use App\Domains\Payments\Features\Api\V2\User\AssignLearnPathToUserFeature;
use App\Domains\Payments\Features\Api\V2\User\LearnPathFiltrationFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class LearnPathController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getLearningPathById($id)
    {
        return $this->serve(GetLearnPathByIdFeature::class);
    }

    public function filterLearnPath()
    {
        return $this->serve(LearnPathFiltrationFeature::class);
    }

    public function showLearnPathsInfo()
    {
        return $this->serve(LearnPathFiltrationFeature::class);
    }


    public function assignLearenPath()
    {
        return $this->serve(AssignLearnPathToUserFeature::class);
    }

}
