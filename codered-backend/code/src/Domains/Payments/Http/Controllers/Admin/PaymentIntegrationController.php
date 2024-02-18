<?php

namespace App\Domains\Payments\Http\Controllers\Admin;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Payments\Rules\PaymentIntegrationPermission;
use App\Foundation\Enum\ColumnTypes;
use App\Domains\Course\Models\Course;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Payments\Enum\PayableType;
use App\Domains\Payments\Models\PaymentIntegration;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Domains\Payments\Models\PackageSubscription;
use Carbon\Carbon;

class PaymentIntegrationController extends CoreController
{
    use HasAuthorization;

    public $domain = "payments";

    public function __construct(PaymentIntegration $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.settings'), null, true);
        $this->pushBreadcrumb(trans('lang.lookups'), null, true);

        $this->select_columns = [
            [
                'name' => trans("course::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => 'Product ID (Samcart)',
                'key'  => 'product_id',
                'type' => ColumnTypes::STRING,
            ],


            [
                'name' => trans("Type"),
                'key'  => function($row) {
                    return $row->payable->name ?? 'Payable Deleted';
                },
                'type' => ColumnTypes::CALLBACK,
            ],


        ];
        $this->searchColumn = ["name", "product_id"];
        parent::__construct();

        $this->permitted_actions = [
            'index'  => PaymentIntegrationPermission::PAYMENT_INTEGRATION_INDEX,
            'create' => PaymentIntegrationPermission::PAYMENT_INTEGRATION_CREATE,
            'edit'   => PaymentIntegrationPermission::PAYMENT_INTEGRATION_EDIT,
            'show'   => null,
            'delete' => PaymentIntegrationPermission::PAYMENT_INTEGRATION_DELETE,
        ];
    }




    public function onCreate()
    {
        parent::onCreate();

        $this->sharedViews();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name"         => ["required", "string", "max:50"],
            'payable_type' => 'required',
            'product_id'   => 'required|unique:payment_integrations'
        ]);

        if($this->request->payable_type == PayableType::SUBSCRIPTION) {
            $this->request->request->add(['payable_id' => $this->request->package_id]);
        }

        if($this->request->payable_type == PayableType::MICRODEGREE) {
            $this->request->request->add(['payable_id' => $this->request->micro_degree_id]);
        }

    }

    public function onEdit()
    {
        parent::onEdit();
        $this->sharedViews();

    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name"         => ["required", "string", "max:50"],
//            'payable_type' => 'required',
            'product_id'   => 'required|unique:payment_integrations,product_id,' . $this->request->payment_integration
        ]);

        if($this->request->payable_type == PayableType::SUBSCRIPTION) {
            $this->request->request->add(['payable_id' => $this->request->package_id]);
        }

        if($this->request->payable_type == PayableType::MICRODEGREE) {
            $this->request->request->add(['payable_id' => $this->request->micro_degree_id]);
        }
    }

    private function sharedViews()
    {
        $micro_degrees_list = Course::where('course_type', CourseType::MICRODEGREE)
        ->orWhere('course_type', CourseType::COURSE_CERTIFICATION)
        ->get()
        ->pluck('internal_name_or_name', 'id');
        $packages_list = PackageSubscription::pluck('name', 'id');

        return view()->share(compact('micro_degrees_list', 'packages_list'));
    }

    public function duplicate()
    {
        $payment_integration = $this->model->find($this->request->payment_integration);

        $newPayement = $payment_integration->replicate();
        $newPayement->name = 'Copy - '. $payment_integration->name;
        $newPayement->created_at = Carbon::now();
        $newPayement->push();

        return redirect("$this->route")->with('success', $this->successMessage(true, 1));
    }

}
