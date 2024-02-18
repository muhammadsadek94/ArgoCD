<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Enum\LessonResourceTypes;
use App\Domains\Course\Import\VoucherImport;
use App\Domains\Course\Jobs\Admin\Voucher\CreateListOFVoucherJob;
use App\Domains\Course\Models\LessonVoucher;
use App\Domains\Course\Rules\CoursePermission;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Course\Models\LessonResource;
use App\Foundation\Http\Controllers\Admin\CoreController;
use Excel;

class LessonVoucherController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(LessonVoucher $model)
    {
        $this->model = $model;

        $this->permitted_actions = [
            'index'  => CoursePermission::COURSE_INDEX,
            'create' => CoursePermission::COURSE_CREATE,
            'edit'   => CoursePermission::COURSE_EDIT,
            'show'   => null,
            'delete' => CoursePermission::COURSE_DELETE,
        ];

        parent::__construct();
    }

    public function store()
    {
        $this->validate($this->request, [
            "voucher"          => ["required", "mimes:xlsx", "unique:lesson_vouchers"],
            'lesson_id'     => ['required', 'exists:lessons,id']
        ]);
        Excel::import( new VoucherImport($this->request),$this->request->voucher);

        return $this->returnMessage(true, 1);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|Voild
     */
    public function destroy($id)
    {
        return ;
        $id = $id ? $id : $this->request->id;
        $row = $this->model->find($id);
        $this->ifMethodExistCallIt('onDestroy', $row);
        $delete = $row->delete();
        $this->ifMethodExistCallIt('isDestroyed', $row);
        return back()->with('success', $this->successMessage(3, null));
    }

}
