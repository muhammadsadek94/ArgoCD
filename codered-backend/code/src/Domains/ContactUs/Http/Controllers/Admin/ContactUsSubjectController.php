<?php

namespace App\Domains\ContactUs\Http\Controllers\Admin;

use App\Domains\ContactUs\Models\ContactUsSubject;
use App\Foundation\Http\Controllers\Admin\CoreController;


class ContactUsSubjectController extends CoreController
{
   function __construct(ContactUsSubject $model)
   {
       $this->model = $model;
       $this->show_columns_html = ["subject_en", "subject_ar"];
       $this->show_columns_select = ["subject_en", "subject_ar"];
       $this->searchColumn = ["subject_en", "subject_ar"];
       parent::__construct();
   }
   public $domain = "contact_us";


    public function onStore()
    {
        $this->validate($this->request, [
            "subject_en" => "required",
            "subject_ar" => "required",
        ]);
    }

    public function onUpdate()
    {
        $this->validate($this->request, [
            "subject_en" => "required",
            "subject_ar" => "required",
        ]);
    }

}
