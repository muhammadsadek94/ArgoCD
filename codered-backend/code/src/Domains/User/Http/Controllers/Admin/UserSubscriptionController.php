<?php

namespace App\Domains\User\Http\Controllers\Admin;

use App\Domains\User\Models\UserSubscription;
use App\Domains\User\Rules\UserPermission;
use App\Foundation\Traits\HasAuthorization;
use App\Foundation\Http\Controllers\Admin\CoreController;

class UserSubscriptionController extends CoreController
{
    use HasAuthorization;

    public $domain = "user";

    public function __construct(UserSubscription $model)
    {
        $this->model = $model;


        $this->permitted_actions = [
            'index'  => UserPermission::USER_INDEX,
            'create' => UserPermission::USER_CREATE,
            'edit'   => UserPermission::USER_EDIT,
            'show'   => null,
            'delete' => UserPermission::USER_DELETE,
        ];

        parent::__construct();
    }


    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
//            "expired_at" => ["required", 'after:today'],
        ]);
    }

    public function update($id)
    {
        $this->ifMethodExistCallIt('onUpdate');
        $row = $this->model->find($id);
        $update = $row->update($this->request->all());
        $row->courseEnrollments()->where('user_id', $row->user_id)->update([
            'expired_at' => $this->request->all(),
        ]);
        return $this->returnMessage($update, 2);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|Voild
     */
    public function destroy($id)
    {
        $id = $id ? $id : $this->request->id;
        $row = $this->model->find($id);
        $this->ifMethodExistCallIt('onDestroy', $row);
        $delete = $row->delete();
        $this->ifMethodExistCallIt('isDestroyed', $row);
        return back()->with('success', $this->successMessage(3, null));
    }
}
