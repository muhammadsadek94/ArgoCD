@extends('admin.layouts.master-without-nav')
@section("title", trans("admin::lang.admin login"))
@section('body_class', 'authentication-bg authentication-bg-pattern')

@push('head')
<style>
    .form-control:focus{
        border:  1px solid #1b1b1b;
    }

    .custom-control-input:checked ~ .custom-control-label::before {
    color: #1b1b1b;
    border-color: #1b1b1b;
    background-color: #1b1b1b;
}
</style>
@endpush

@section('content')
    <div class="account-pages d-flex justify-content-center align-items-center h-90vh">
        <div class="container">
            <div class="row flex-column justify-content-center align-items-center mt-3">
                <div class="col-md-8 col-lg-5 mb-5">
                    <div class="text-center w-75 m-auto">
                        <a>
                            <span><img src="{{ asset('/assets/imgs/logo-transparent.png') }}"
                                    class="login-logo img-fluid" alt=""></span>
                        </a>
                    </div>
                </div>
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="text-left m-auto">
                        <h3 class="text-black mb-2 mt-3 font-light">@lang("admin::lang.admin login")</h3>
                    </div>
                </div>
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern form-box-shadow">

                        <div class="card-body p-4">
                            @if(session()->has("error"))
                                <div class="alert alert-danger">
                                    {{session()->get("error")}}
                                </div>
                            @endif
                            {!! Form:: open(['method'=>'POST', 'files'=>true, 'route' => "{$admin_base_url}.login"]) !!}
                            <div class="form-group mb-2 {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-form-label text-black col-lg-3 col-sm-12"
                                       for="name"> @lang('admin::lang.email') </label>
                                <div class="col-12">
                                    {!! Form::text('email', null, ['class' => 'form-control form-control-solid radius-5 text-black']) !!}
                                    <span class="m-form__help form-validation-help">
                                   {{ $errors->first('email') }}
                                </span>
                                </div>
                            </div>
                            <div class="form-group mb-2 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-form-label text-black col-lg-3 col-sm-12"
                                       for="password"> @lang('admin::lang.password') </label>
                                <div class="col-12">
                                    {!! Form::password('password', ['class' => 'form-control form-control-solid radius-5 text-black']) !!}
                                    <span class="m-form__help form-validation-help">
                                   {{ $errors->first('password') }}
                                </span>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox ml-2">
                                    <input name="remember" type="checkbox" class="custom-control-input"
                                           id="checkbox-signin">
                                    <label class="custom-control-label text-black"
                                           for="checkbox-signin">@lang("admin::lang.Remember me")</label>
                                </div>
                            </div>
                            <div class="form-group mb-0 text-center col-12 mt-2 mb-1">
                                <button class="btn btn-primary btn-block" type="submit"> @lang("admin::lang.Log In") </button>
                            </div>

                            {!! Form::close() !!}

                        </div> <!-- end card-body -->
                    </div>
                    {{-- <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p> <a href="{{ url("{$admin_base_url}/password/reset") }}" class="text-white-50 ml-1">@lang('admin::lang.Forgot your password?')</a></p>
                        </div> <!-- end col -->
                    </div> --}}
                    <!-- end card -->

                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->
@endsection
