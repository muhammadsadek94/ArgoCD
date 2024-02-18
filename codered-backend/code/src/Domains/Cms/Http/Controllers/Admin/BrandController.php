<?php

namespace App\Domains\Cms\Http\Controllers\Admin;

use App\Domains\Cms\Models\Brand;
use App\Domains\Cms\Models\Slider;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;

class BrandController extends CoreController
{
    use HasAuthorization;

    public $domain = "cms";

    public function __construct(Brand $model)
    {
        $this->model = $model;

        $this->select_columns = [

            [
                'name' => trans("user::lang.image"),
                'key'  => 'image',
                'type' => ColumnTypes::IMAGE,
            ],

            [
                'name' => 'Alt Text',
                'key'  => 'alt_text',
                'type' => ColumnTypes::STRING,
            ],


        ];
        $this->searchColumn = ["alt_text"];
        parent::__construct();

        // $this->permitted_actions = [
        //     'index' => SliderPermission::Slider_INDEX,
        //     'create' => SliderPermission::Slider_CREATE,
        //     'edit' => SliderPermission::Slider_EDIT,
        //     'delete' => SliderPermission::Slider_DELETE,
        // ];
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
            "alt_text"        => ["required", "string", "max:50"]
        ]);
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
            "alt_text"        => ["required", "string", "max:50"]
        ]);
    }


    public function sharedViews()
    {

    }

    public function postUploadPicture()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }

}
