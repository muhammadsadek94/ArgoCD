<?php

namespace App\Domains\Reports\Exports;

use App\Domains\Course\Models\LessonMsq;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class LessonQuizesExport implements FromView
{

    /**
     * @var array
     */
    //private $user_details;

    use Exportable;


    public function view(): View
    {
        return view('reports::report-templates.quizes-report', [
            'quizes' => LessonMsq::with('lesson.course')->get()
        ]);

    }
}
