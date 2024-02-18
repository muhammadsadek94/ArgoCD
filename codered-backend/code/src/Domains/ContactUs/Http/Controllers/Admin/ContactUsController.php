<?php

namespace App\Domains\ContactUs\Http\Controllers\Admin;

use App\Domains\ContactUs\Enum\ContactUsStatus;
use App\Domains\ContactUs\Mails\ReplyContactUsMail;
use App\Domains\ContactUs\Models\ContactUs;
use App\Domains\ContactUs\Rules\ContactUsPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;

class ContactUsController extends CoreController
{
    use HasAuthorization;

    public function __construct(ContactUs $model)
    {
        $this->model = $model;
        $this->searchColumn = ["first_name", "last_name"];

        $this->permitted_actions = [
            'index'  => ContactUsPermission::CONTACTUS_INDEX,
            'create' => null,
            'edit'   => null,
            'show'   => ContactUsPermission::CONTACTUS_REPLY,
            'delete' => ContactUsPermission::CONTACTUS_DELETE
        ];
        parent::__construct();
    }

    public $domain = 'contact_us';

    public function index()
    {
        $this->pushBreadcrumb(trans('lang.index'), null, true);

        $this->ifMethodExistCallIt('onIndex');
        $this->request->flash();
        $search = $this->request->search;

        $rows = $this->model->orderBy($this->orderBy[0], $this->orderBy[1]);

        if (!empty($search)) {
            $rows = $rows->whereRaw("concat(first_name, ' ', last_name) LIKE ?", "%{$search}%");
        }

        $rows = $rows->paginate($this->perPage);

        return $this->view('index', [
            'rows'           => $rows,
            'select_columns' => $this->select_columns,
            'breadcrumb'     => $this->breadcrumb,
        ]);
    }

    public function onShow()
    {
        parent::onShow();
        $contact_us = ContactUs::find($this->request->contact_u);
        if ($contact_us->status == ContactUsStatus::UNSEEN) {
            $contact_us->update(['status' => ContactUsStatus::SEEN]);
        }
    }

    public function reply()
    {
        $this->hasPermission(ContactUsPermission::CONTACTUS_REPLY);

        $this->validate($this->request, [
            'email'         => ['required', 'email'],
            'reply_message' => ['required', 'min:5'],
            'reply_subject' => ['required', 'min:1']
        ]);

        \Mail::to($this->request->email)->send(new ReplyContactUsMail($this->request->reply_message, $this->request->reply_subject));
        $contact_us = ContactUs::find($this->request->contact_us);

        $contact_us->update(['status' => ContactUsStatus::REPLIED]);
        return redirect('/admin/contact-us')->withSuccess(trans('contact_us::lang.mail sent successfully'));
    }
}
