@push('head')
    <link rel="stylesheet" href="{{ asset('/admin/assets/libs/dropify/dropify.min.css') }}">

    <style>
        .dropify-filename {
            display: none;
        }
    </style>
@endpush
@push('script-bottom')
    <script src="{{ asset('/admin/assets/libs/dropify/dropify.js') }}"></script>
    <script>
        $(document).ready(function () {
            const dropifyEvent = $('#{{ $name }}').dropify({
                messages: {
                    'default': ' ',
                    'replace': ' ',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong happended.'
                }
            });

            dropifyEvent.on('dropify.fileReady', function (event, element) {
                let file = event.target.files[0];
                if (file != undefined && file != '' && file != null) {
                    var formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', window.app_variables.csrfToken);
                    $.ajax({
                        type: "POST",
                        url: {!! isset($endpoint) ? "'$endpoint'" : '`${window.app_variables.ADMIN_PATH}/user/action/upload-profile-picture`' !!},
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            $("[name='{{ $name }}']").val(res.data.id);
                        },
                        error: function (data) {
                            var errors = data.responseJSON;
                            toastr['error'](errors.errors.file[0]);
                            let dr = dropifyEvent.data('dropify');
                            dr.resetPreview();
                            dr.clearElement();
                        }
                    });
                } else {
                    toastr['error']('File not found');
                }
            });

            dropifyEvent.on('dropify.afterClear', function(event, element){
                $("[name='{{ $name }}']").val(null);
            });


        })

    </script>
@endpush
<div class="form-group {{ $cols ?? 'col-lg-6 col-12' }} {{ $errors->has($name) ? ' has-danger' : '' }}">
    @php
        $isMandatory = in_array('required', $form_options ?? []) ? 'mandatory' : '';
    @endphp
    {!! Form::label($name, $label, ['class'=>"form-control-label {$isMandatory}"]) !!}
    {!! Form::file("", array_merge(['class'=> ($errors->has($name) ? 'form-control is-invalid' : 'form-control'), 'id' => $name, 'data-default-file' => $placeholder ?? null], [])) !!}
    {!! Form::hidden($name, $value ?? null, [($isMandatory ?? 'required')]) !!}
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
    @isset($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endisset
</div>
