@push('head')
    <link rel="stylesheet" href="{{ asset('/admin/assets/libs/intl-tel/css/intlTelInput.css') }}">

    <style>
        .phone-container.has-danger .iti__flag-container{
            top: -20px !important;
        }

        .iti {
            margin: 0;
            padding: 0;
        }

    </style>
@endpush


@push('script-bottom')
    <script src="{{ asset('/admin/assets/libs/intl-tel/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('/admin/assets/libs/intl-tel/js/utils.js') }}"></script>

    <script>
        $(document).ready(function () {
            var phoneNumberInput = document.querySelector("#{{ $name }}");
            window.phoneNumberPlug = window.intlTelInput(phoneNumberInput, {
                autoPlaceholder: 'off',
                placeholderNumberType: true,
                initialCountry: "auto",
                // separateDialCode: true,
                formatOnDisplay: false,
                customContainer: 'col-12 row {{ $name }}-container',
                // initialCountry: 'eg',
                preferredCountries: ['eg'],
                // onlyCountries: ['eg', 'dz', 'bh', 'iq', 'jo', 'kw', 'lb', 'ly', 'ma', 'om', 'ps', 'qa', 'sa', 'sd', 'sy', 'tn', 'ae', 'ye'],
                nationalMode: false
            });

            $("#{{ $name }}").closest('form').on('submit', function(e) {
                let el = $("#{{ $name }}");
                let val = el.val();
                let newVal = val.removeCharacter('+');
                el.val(newVal);
            });

            var isPageInitWithValue = phoneNumberPlug.getNumber().length > 3;

            phoneNumberInput.addEventListener("countrychange", function (e) {
                if (!isPageInitWithValue) {
                    let countryData = phoneNumberPlug.getSelectedCountryData(); // get country data as obj
                    let countryCode = countryData.dialCode; // using updated doc, code has been replaced with dialCode
                    if (countryCode != undefined) {
                        formatPhoneNumber(phoneNumberInput, true)
                    }
                }
                isPageInitWithValue = false
            });


            function formatPhoneNumber(phoneNumberPlug, initPLug = false) {
                if (initPLug == true) {
                    let inputVal = $('#{{ $name }}').val();
                    if(inputVal.length > 0 && inputVal[0] != '+')
                        phoneNumberPlug.setNumber(`+${inputVal}`);

                    return ;
                }

                let phoneNumber = phoneNumberPlug.getNumber();
                phoneNumber = phoneNumber.removeCharacter(' ')
                let countryData = phoneNumberPlug.getSelectedCountryData(); // get country data as obj
                let countryCode = countryData.dialCode; // using updated doc, code has been replaced with dialCode
                phoneNumber = phoneNumber.removeCharacter('+');

                phoneNumber = `+${phoneNumber}`;

                phoneNumber = phoneNumber.replace(`${countryCode}-`, `${countryCode}`);
                phoneNumber = phoneNumber.replace(countryCode, `${countryCode}-`);
                $('#{{ $name }}').val(phoneNumber);
            }

            formatPhoneNumber(window.phoneNumberPlug, true);

            $(document).on('change keyup', '#{{ $name }}', function () {
                $('span.invalid-feedback').remove();
                $('form input.is-invalid, form textarea.is-invalid, form select.is-invalid ').removeClass('is-invalid');
                $('form div.has-danger').removeClass('has-danger');

                let val = $(this).val();
                if(val[0] != '+') {
                    formatPhoneNumber(window.phoneNumberPlug)
                    return;
                }

                if(phoneNumberPlug.isValidNumber() == false) {
                    $('#{{ $name }}').closest('form').find("button[type='submit']").prop('disabled', true)
                    $('#{{ $name }}').parent().addClass('has-danger');
                    $('#{{ $name }}').addClass('is-invalid');
                    $('#{{ $name }}').parent().append('<span class="invalid-feedback"> <strong> Invalid phone number </strong> </span>');
                }else {
                    $('#{{ $name }}').closest('form').find("button[type='submit']").prop('disabled', false)
                }

                formatPhoneNumber(window.phoneNumberPlug)
            });

            $(document).on('click', '.{{ $name }}-container li', function (e) {
                let countryData = phoneNumberPlug.getSelectedCountryData(); // get country data as obj
                let countryCode = countryData.dialCode; // using updated doc, code has been replaced with dialCode
                $('#{{ $name }}').val(`+${countryCode}`);
            });



            $(document).on('blur', '#{{ $name }}', function () {
                let val = $(this).val()
                let newVal = val.removeCharacter('+');
                $(this).val(newVal);
            });

        })

    </script>
@endpush


<div class="form-group {{isset($cols) ? $cols : 'col-lg-6 col-12'}} {{ $errors->has($name) ? ' has-danger' : '' }}">
    <div>
        @php
            $isMandatory = in_array('required', $form_options ?? []) ? 'mandatory' : '';
        @endphp
        {!! Form::label($name, $label, ['class'=>"form-control-label {$isMandatory}"]) !!}
        {!! Form::text($name, $value ?? null, ['class'=> ($errors->has($name) ? 'form-control is-invalid' : 'form-control'), 'id' => $name] + ($form_options ?? [])) !!}
    </div>
    <div class="row">
        @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        @isset($help)
            <small class="form-text text-muted">{{ $help }}</small>
        @endisset
    </div>
</div>
