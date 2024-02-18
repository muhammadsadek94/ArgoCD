<?php

namespace App\Domains\Admin\Http\Controllers\Admin;

use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Rules\PasswordRule;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class ProfileController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getIndex()
    {
        $user = $this->request->user('admin');
        return view('admin::admin.profile', compact('user'));
    }

    public function postIndex()
    {
        $user = $this->request->user('admin');

        $this->validate(request(), [
            'name'     => 'required',
            'email'    => "required|email|unique:admins,email,{$user->id}",
            'password' => ['confirmed', 'nullable', new PasswordRule, 'min:10'],
            'phone'    => 'required',
        ]);

        if (request()->has('password'))
            $user = $user->update(request()->all());
        else
            $user = $user->update(request()->except('password'));

        if ($this->request->ajax())
            return response(['status' => "true", 'message' => trans('lang.saved_successful_message')]);
        return back()->withSuccess(trans('lang.saved_successful_message'));
    }

    public function postUploadPicture()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }
}
