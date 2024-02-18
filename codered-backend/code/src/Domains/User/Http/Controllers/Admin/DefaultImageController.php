<?php

namespace App\Domains\User\Http\Controllers\Admin;

use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Features\Api\V2\User\Profile\UploadDefaultImagesFeature;
use App\Domains\User\Rules\UserPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;
use ColumnTypes;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class DefaultImageController extends CoreController
{

    use HasAuthorization;

    public $domain = "user";

    public function __construct(Upload $model)
    {

        $this->model = $model;
        $this->select_columns = [
            [
                'name' => trans("user::lang.image"),
                'key' => 'image',
                'type' => ColumnTypes::IMAGE,
            ],

        ];
        $this->isShowable = true;
        $this->permitted_actions = [
            'index' => UserPermission::USER_INDEX,
            'create' => UserPermission::USER_CREATE,
        ];

        parent::__construct();
    }

    public function callbackQuery($query)
    {
        $query = $query->where('is_default_profile_image', true);


        return $query;
    }


    public function onCreate()
    {
        parent::onCreate();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            'last_name' => 'string|max:50|nullable',
        ]);
    }

    public function store()
    {

        $this->request->validate([
            "file" => "required|mimes:jpeg,jpg,png,webp"
        ]);

        $this->serve(UploadDefaultImagesFeature::class);
        return $this->returnMessage(true, 1);
    }
}
