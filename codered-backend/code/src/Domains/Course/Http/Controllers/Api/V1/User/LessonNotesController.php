<?php

namespace App\Domains\Course\Http\Controllers\Api\V1\User;

use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Course\Features\Api\V1\User\AddNoteFeature;
use App\Domains\Course\Features\Api\V1\User\UpdateNoteFeature;
use App\Domains\Course\Features\Api\V1\User\DeleteNoteFeature;


class LessonNotesController extends Controller
{


    public function store()
    {
        return $this->serve(AddNoteFeature::class);
    }


    public function update()
    {
        return $this->serve(UpdateNoteFeature::class);
    }

    public function destroy()
    {

        return $this->serve(DeleteNoteFeature::class);
    }


}
