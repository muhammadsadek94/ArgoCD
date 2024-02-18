@extends('admin.layouts.master-without-nav')
@section("title", trans("Login"))
@section('body_class', 'authentication-bg authentication-bg-pattern')

@push('head')
<style>
    .h-40px{
        height: 40px;
    }
    
    .w-75px{
        width: 76px;
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
                        <h3 class="text-black mb-2 mt-3 font-light">OTP</h3>
                    </div>
                </div>
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern form-box-shadow">

                        <div class="card-body p-4">

                            <form method="POST" action="{{ route('admin.code') }}">
                                @csrf

                                <div class="form-group mb-3"  {{ $errors->has('code') ? ' has-error' : '' }}>
                                    <label for="code"  class="col-form-label col-lg-6 col-sm-12 px-0">OTP</label>
                                    <div class="mb-10 px-md-10">
                                        <div class="fw-bolder text-start text-dark fs-6 mb-1 ms-1">Please enter the otp that's sent to your phone number</div>
                                        @error('code')
                                            <span class=" fs-6 text-start my-1 ms-1" role="alert">
                                                <small>{{ $message }}</small>
                                            </span>
                                        @enderror
                                        @error('code.*')
                                            <span class=" fs-6 text-start my-1 ms-1" role="alert">
                                                <small>{{ $message }}</small>
                                            </span>
                                        @enderror
                                        <div class="d-flex flex-wrap flex-stack">
                                            <div>
                                                <input name="code[]" onPaste="onPaste(event)" onkeyup="onDigitInput(event)" type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid otp-input radius-5 h-40px w-75px fs-2qx text-center mr-3 my-2" />
                                            </div>
                                            <div>
                                                <input name="code[]" onPaste="onPaste(event)" onkeyup="onDigitInput(event)" type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid otp-input radius-5 h-40px w-75px fs-2qx text-center mr-3 my-2" />
                                            </div>
                                            <div>
                                                <input name="code[]" onPaste="onPaste(event)" onkeyup="onDigitInput(event)" type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid otp-input radius-5 h-40px w-75px fs-2qx text-center mr-3 my-2" />
                                            </div>
                                            <div>
                                                <input name="code[]" onPaste="onPaste(event)" onkeyup="onDigitInput(event)" type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid otp-input radius-5 h-40px w-75px fs-2qx text-center  my-2" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-12 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary px-4">
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


@push('script-bottom')
<script>
    function onDigitInput(event) {
			let element;
			if (isNaN(event.target.value)) return;
			if (event.target.value || event.code === 'Backspace') {
			if (
				event.code !== 'Backspace' &&
				event.target.parentElement.nextElementSibling
			)
				element = event.target.parentElement.nextElementSibling.children[0];

			if (
				event.code === 'Backspace' &&
				event.target.parentElement.previousElementSibling
			)
				element = event.target.parentElement.previousElementSibling.children[0];

			if (element == null) return;
			else element.focus();
			}
		}

		function onPaste(event) {
			let clipboardData = event.clipboardData || window.clipboardData;
			let pastedText = clipboardData.getData('text');
			if (isNaN(pastedText)) return;
			let pastedArray = Array.from(pastedText);
			var list = document.getElementsByClassName("otp-input");
			for (var i = 0; i < pastedArray.length; i++) {
				list[i].value = pastedArray[i];
			}
		}
</script>
@endpush