@extends('admin.layouts.master-without-nav')
@section("title", trans("Login"))
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
                                <h3 class="text-muted mb-4 mt-3">@lang("Please enter the otp that's sent to your phone number")</h3>
                            </div>

                            <form method="POST" action="{{ route('admin.code') }}">
                                @csrf

                                <div class="form-group mb-3"  {{ $errors->has('code') ? ' has-error' : '' }}>
                                    <label for="code"  class="col-form-label col-lg-6 col-sm-12">@lang('Code') </label>
                                    <div class="col-12">
                                        <input id="code" type="text"
                                               class="form-control @error('code') is-invalid @enderror" name="code"
                                               value="{{ old('code') }}" required autocomplete="code"
                                               autofocus>
                                        @error('code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            @lang('Verify')
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p> <a href="{{ url("{$admin_base_url}/login") }}" class="text-white-50 ml-1">@lang("Logout ?")</a></p>
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
