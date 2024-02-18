@extends('admin.layouts.main')
@section('title',trans("lang.Profile"))
@section('content')
    @include('admin.layouts.breadcrumb', [
         'page_title' => trans('lang.my_account'),
         'crumbs' => [
             [
                 'title' => trans('lang.my_account'),
                 'active' => false,
                 'url' => null
             ],
             [
                 'title' => trans('admin::lang.update_profile'),
                 'active' => true,
                 'url' => null
             ]
         ]
     ])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body mt-2">
                    {{-- <h4 class="header-title">@lang("lang.edit")</h4> --}}
                    {!! Form::model($user,['method'=>'POST', 'files'=>true, 'data-toggle' => 'ajax']) !!}
                        <div class="row">
                            @include('admin.components.inputs.image', ['name' => 'image_id', 'label' => trans('user::lang.image'),'cols' => 'col-lg-4 offset-lg-4 col-12 ', 'value' => $user->image_id ?? null, 'placeholder' => isset($user->image) ? asset("{$user->image->full_url}") : null, 'endpoint' => url(\App\Foundation\Enum\Constants::ADMIN_BASE_URL . '/admin/my-account/update-profile/actions/upload-profile-picture')])
                            <div class="col-12"></div>
                            @include('admin.components.inputs.text', ['name' => 'name', 'label' => trans('admin::lang.name'), 'extra_options'=> ['required']])
                            @include('admin.components.inputs.email', ['name' => 'email', 'label' => trans('admin::lang.email'), 'extra_options'=> ['required']])
                            @include('admin.components.inputs.text', ['name' => 'phone', 'label' => trans('admin::lang.phone'), 'extra_options'=> ['required', 'placeholder' => 'EX: 919150000000']])
                            <div class="col-12"></div>
                            @include('admin.components.inputs.password', ['name' => 'password', 'label' => trans('admin::lang.password')])
                            @include('admin.components.inputs.password', ['name' => 'password_confirmation', 'label' => trans('admin::lang.password_confirmation')])

                            @include('admin.components.inputs.success-btn', ['button_text' => trans('lang.save')])
                        </div>
                    {!! Form::close() !!}
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>

@endsection
