<?php

namespace App\Domains\Cms\Http\Controllers\Admin;

use App\Domains\Cms\Models\Brand;
use App\Domains\Cms\Models\Slider;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;

class SliderController extends CoreController
{
    use HasAuthorization;

    public $domain = "cms";

    public function __construct(Slider $model)
    {
        $this->model = $model;

        $this->select_columns = [

            [
                'name' => trans("user::lang.image"),
                'key'  => 'image',
                'type' => ColumnTypes::IMAGE,
            ],

            [
                'name' => 'title',
                'key'  => 'title',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => 'sub title',
                'key'  => 'sub_title',
                'type' => ColumnTypes::STRING,
            ]
        ];
        $this->searchColumn = ["title"];
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
            "title"        => ["required", "string", "max:50"],
            "sub_title"        => ["required", "string", "max:50"],
            'description'  => ['required', 'string'],

            'title_color' => ['required'],
            'sub_title_color' => ['required'],
            'description_color' => ['required'],

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
            "title"        => ["required", "string", "max:50"],
            "sub_title"        => ["required", "string", "max:50"],
            'description'  => ['required', 'string'],
        ]);
    }

    public function isUpdated($row) {
        $row->brands()->sync($this->request->brands);
    }

    public function isStored($row) {
        $row->brands()->sync($this->request->brands);
    }


    public function sharedViews()
    {
        $brands = Brand::pluck('alt_text', 'id');
        view()->share(compact('brands'));
    }

    public function postUploadPicture()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }

}
