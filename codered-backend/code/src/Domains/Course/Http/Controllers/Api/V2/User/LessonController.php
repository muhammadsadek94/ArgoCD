<?php

namespace App\Domains\Course\Http\Controllers\Api\V2\User;

use App\Domains\Course\Features\Api\V2\User\AddCommentFeature;
use App\Domains\Course\Features\Api\V2\User\AddProjectApplicationFeature;
use App\Domains\Course\Features\Api\V2\User\AssignVoucherFeature;
use App\Domains\Course\Features\Api\V2\User\GetFreeLessonFeature;
use App\Domains\Course\Features\Api\V2\User\GetILabSessionFeature;
use App\Domains\Course\Features\Api\V2\User\GetLessonFeature;
use App\Domains\Course\Features\Api\V2\User\GetUserNoteFeature;
use App\Domains\Course\Features\Api\V2\User\GetVitalSourceSessionFeature;
use App\Domains\Course\Features\Api\V2\User\InteractWithLessonTaskFeature;
use App\Domains\Course\Features\Api\V2\User\MarkLessonAsWatchedFeature;
use App\Domains\Course\Features\Api\V2\User\StoreLessonWatchedTimeFeature;
use App\Domains\Course\Features\Api\V2\ValidateCyberQSessionFeature;
use App\Domains\Course\Services\VitalSource\VitalSourceService;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class LessonController extends Controller
{
    public function show()
    {
        return $this->serve(GetLessonFeature::class);
    }

    public function free()
    {
        return $this->serve(GetFreeLessonFeature::class);
    }

    public function getUserNotes()
    {
        return $this->serve(GetUserNoteFeature::class);
    }

    public function postWatched()
    {
        return $this->serve(MarkLessonAsWatchedFeature::class);
    }

    public function postWatchedTime()
    {
        return $this->serve(StoreLessonWatchedTimeFeature::class);
    }

    public function getIlabSession()
    {
        return $this->serve(GetILabSessionFeature::class);
    }

    public function postTaskInteraction()
    {
        return $this->serve(InteractWithLessonTaskFeature::class);
    }

    public function assignVoucher()
    {
        return $this->serve(AssignVoucherFeature::class);
    }

    public function addProjectApplication()
    {
        return $this->serve(AddProjectApplicationFeature::class);
    }

    public function addCommentProjectApplication()
    {
        return $this->serve(AddCommentFeature::class);
    }

    public function createVitalSourceSession(Request $request)
    {
        return $this->serve(GetVitalSourceSessionFeature::class);
    }

    public function validateCyberQSession()
    {
        return $this->serve(ValidateCyberQSessionFeature::class);
    }
}
