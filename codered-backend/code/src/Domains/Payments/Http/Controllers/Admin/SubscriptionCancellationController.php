<?php

namespace App\Domains\Payments\Http\Controllers\Admin;

use App\Domains\Payments\Rules\SubscriptionCancellationPermission;
use ColumnTypes;
use App\Domains\User\Rules\GoalPermission;
use App\Foundation\Traits\HasAuthorization;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Domains\Payments\Models\SubscriptionCancellationRequest;
use App\Domains\Payments\Enum\SubscriptionCancellationRequestsStatus;

class SubscriptionCancellationController extends CoreController
{
    use HasAuthorization;

    public $domain = "payments";

    public function __construct(SubscriptionCancellationRequest $model)
    {
        $this->model = $model;

        $this->select_columns = [
            [
                'name' => trans("user::lang.name"),
                'key' => 'name',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("user::lang.email"),
                'key' => 'email',
                'type' => ColumnTypes::STRING,
            ],
//            [
//                'name' => trans("Status"),
//                'key' => function (SubscriptionCancellationRequest $row) {
//                    return $row->readable_status();
//                },
//                'type' => ColumnTypes::CALLBACK,
//            ],
            [
                'name' => trans("Reason"),
                'key' => function (SubscriptionCancellationRequest $row) {
                    return $row->readable_type();
                },
                'type' => ColumnTypes::CALLBACK,
            ],
//            [
//                'name' => trans("Subscription ID"),
//                'key' => function (SubscriptionCancellationRequest $row) {
//                    return ($row->user && $row->user->active_subscription) ?
//                        $row->user->active_subscription->implode('subscription_id', ',') :
//                        'No Subscriptions';
//
//                },
//                'type' => ColumnTypes::CALLBACK,
//            ],

        ];
        $this->searchColumn = ["name", "email"];
        parent::__construct();

        $this->permitted_actions = [
            'index' => SubscriptionCancellationPermission::SUBSCRIPTION_CANCELLATION_INDEX,
            'create' => null,
            'edit' => SubscriptionCancellationPermission::SUBSCRIPTION_CANCELLATION_EDIT,
            'show' => null,
            'delete' => SubscriptionCancellationPermission::SUBSCRIPTION_CANCELLATION_DELETE,
        ];
    }

    public function cancel($id)
    {
        parent::onEdit();

        $this->model->find($id)->update([
            'status' => SubscriptionCancellationRequestsStatus::CANCELLED
        ]);

        return redirect("{$this->route}")->with('success', 'Subscription cancelled');
    }

}
