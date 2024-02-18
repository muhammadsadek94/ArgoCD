<div class="form-group {{ $cols ?? 'col-lg-6 col-12' }} {{ $errors->has($name) ? ' has-danger' : '' }}">
	@php
		$isMandatory = in_array('required', $form_options ?? []) ? 'mandatory' : '';
	@endphp
	{!! Form::label($name, $label, ['class'=>"form-control-label {$isMandatory}"]) !!}
	{!! Form::file("", array_merge(['class'=> ($errors->has($name) ? 'form-control is-invalid' : 'form-control'), 'id' => "{$name}-form"], $form_options)) !!}
	{!! Form::hidden($name, $value ?? null) !!}
	
	@error($name)
	<span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
	@enderror
	@isset($help)
		<small class="form-text text-muted">{{ $help }}</small>
	@endisset
</div>


@push('script-bottom')
	<script>
        $(document).ready(function () {
            $(document).on('change', "#{{ $name }}-form", function (e) {
                let file = e.target.files[0];
                if (file != undefined && file != '' && file != null) {
                    var formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', window.app_variables.csrfToken);
                    $.ajax({
                        type: "POST",
                        url: {!! "'$endpoint'" !!},
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
                        }
                    });
                } else {
                    toastr['error']('File not found');
                }
            })
        })
	</script>
@endpush
