@extends('admin.layouts.master-without-nav')
@section("title", trans("admin::lang.Reset Your Password"))
@section('body_class', 'authentication-bg authentication-bg-pattern')

@section('content')

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <a>
                                    <span><img src="{{ asset('/assets/imgs/logo-transparent.png') }}"
                                               class="login-logo img-fluid" alt=""></span>
                                </a>
                                <h3 class="text-muted mb-4 mt-3">@lang("admin::lang.Reset Your Password")</h3>
                            </div>

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group mb-3"  {{ $errors->has('email') ? ' has-error' : '' }}>
                                    <label for="email"  class="col-form-label col-lg-6 col-sm-12">@lang('admin::lang.email') </label>
                                    <div class="col-12">
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ $email ?? old('email') }}" required autocomplete="email"
                                               autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password"
                                           class="col-form-label col-lg-6 col-sm-12">@lang('admin::lang.password')</label>

                                    <div class="col-12">
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password" required autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password-confirm" class="col-form-label col-lg-6 col-sm-12">@lang('admin::lang.Confirm Password')</label>

                                    <div class="col-12">
                                        <input id="password-confirm" type="password" class="form-control"  name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            @lang('admin::lang.Reset Password')
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p> <a href="{{ url("{$admin_base_url}/login") }}" class="text-white-50 ml-1">@lang("admin::lang.Want to login?")</a></p>
                        </div> <!-- end col -->
                    </div>

                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


@endsection
