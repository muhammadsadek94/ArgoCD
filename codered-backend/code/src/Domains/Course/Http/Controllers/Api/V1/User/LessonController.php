<?php

namespace App\Domains\Course\Http\Controllers\Api\V1\User;

use App\Domains\Course\Features\Api\V1\User\AssignVoucherFeature;
use App\Domains\Course\Features\Api\V1\User\GetILabSessionFeature;
use App\Domains\Course\Features\Api\V1\User\GetUserNoteFeature;
use App\Domains\Course\Features\Api\V1\User\InteractWithLessonTaskFeature;
use App\Domains\Course\Features\Api\V1\User\StoreLessonWatchedTimeFeature;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Course\Features\Api\V1\User\GetLessonFeature;
use App\Domains\Course\Features\Api\V1\User\MarkLessonAsWatchedFeature;

class LessonController extends Controller
{

    public function show()
    {
        return $this->serve(GetLessonFeature::class);
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

}
