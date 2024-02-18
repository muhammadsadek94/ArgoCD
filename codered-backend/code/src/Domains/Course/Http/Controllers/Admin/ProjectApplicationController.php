<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Admin\Jobs\SaveLogFilesJob;
use App\Domains\Admin\Models\Admin;
use App\Domains\Comment\Jobs\Admin\CreateCommentJob;
use App\Domains\Course\Enum\LessonResourceTypes;
use App\Domains\Course\Enum\ProjectApplicationStatus;
use App\Domains\Course\Import\VoucherImport;
use App\Domains\Course\Jobs\Admin\Voucher\CreateListOFVoucherJob;
use App\Domains\Course\Models\LessonVoucher;
use App\Domains\Course\Rules\CoursePermission;
use App\Domains\Uploads\Enum\UploadType;
use App\Domains\Uploads\Jobs\UploadFileJob;
use App\Domains\User\Mails\SendCommentMail;
use App\Domains\User\Mails\SendPasswordMail;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Course\Models\LessonResource;
use App\Domains\Course\Models\ProjectApplication;
use App\Domains\Course\Rules\ProjectApplicationPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;
use ColumnTypes;
use Excel;
use http\Env\Request;
use Illuminate\Http\UploadedFile;
use finfo;

class ProjectApplicationController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(ProjectApplication $model)
    {
        $this->model = $model;

        $this->isShowable = true;

        $this->select_columns = [
            [
                'name' => trans("course::lang.course_name"),
                'key' => function ($row) {
                    return $row->course->name ?? null;
                },
                'type' => ColumnTypes::CALLBACK,
            ],
            [
                'name' => trans("course::lang.lesson_name"),
                'key' => function ($row) {
                    return $row->lesson->name ?? null;
                },
                'type' => ColumnTypes::CALLBACK,
            ],

            [
                'name' => trans("course::lang.user_name"),
                // 'key'  => function ($row) {
                //     return $row->user->first_name ?? null;


                'key' => function ($row) {
                    return "<a href='" . url('/admin/user/' . $row->user->id) . "'>{$row->user->first_name }</a>";
                },
                'type' => ColumnTypes::CALLBACK,
            ],

            [
                'name' => trans("Status"),
                'key' => 'status',
                'type' => ColumnTypes::LABEL,
                'label_values' => [
                    ProjectApplicationStatus::SUBMITTED => [
                        'text' => trans('Submitted'),
                        'class' => 'badge badge-info',
                    ],
                    ProjectApplicationStatus::UNDER_REVIEW => [
                        'text' => trans('Under Review'),
                        'class' => 'badge badge-warning',
                    ],
                    ProjectApplicationStatus::REVIEW_COMPLETED => [
                        'text' => trans('Review Completed'),
                        'class' => 'badge badge-success',
                    ],

                ]
            ],


        ];
        $this->permitted_actions = [
            'index' => ProjectApplicationPermission::PROJECT_APPLICATION_INDEX,
            'show' => null,
            'create' => ProjectApplicationPermission::PROJECT_APPLICATION_CREATE,
            'delete' => ProjectApplicationPermission::PROJECT_APPLICATION_DELETE,
        ];
        $this->searchColumn = ["name"];

        parent::__construct();
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */

    public function addComment(\Illuminate\Http\Request $request)
    {
        $data = $request->all();
        $admin = auth()->guard('admin')->user();
        $admin_id = $admin->id;
        $comment = CreateCommentJob::dispatchNow($data['comment'], $admin_id, Admin::class, $request['id'], ProjectApplication::class);
        $project = ProjectApplication::find($request['id']);
        try {
            \Mail::to($project->user->email)
                ->send(new SendCommentMail('recent comment on' . $project->lesson->name, $comment,$project->user, $admin, $project->lesson->name));
        }
        catch (Swift_TransportException $err) {
        }
        return response()->json(['status' => 'true', 'message' => $this->successMessage(2, 'comment has been sent!')]);

    }

    public function completeProject(ProjectApplication $project)
    {
        $project->update(['status' => ProjectApplicationStatus::REVIEW_COMPLETED]);
        return response()->json(['status' => 'true', 'message' => $this->successMessage(2, 'status has been changed!')]);
    }

    public function changeStatus(ProjectApplication $project)
    {
        $project->update(['status' => $this->request->status]);
        return response()->json(['status' => 'true', 'message' => $this->successMessage(2, 'status has been changed!')]);
    }


}
