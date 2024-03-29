<div class="form-group {{ $cols ?? 'col-lg-6 col-12' }} {{ $errors->has($name) ? ' has-danger' : '' }}">
    @php
        $isMandatory = in_array('required', $form_options ?? []) ? 'mandatory' : '';
    @endphp
    {!! Form::label($name, $label, ['class'=>"form-control-label {$isMandatory}"]) !!}
    {!! Form::select($name, $select_options, ($value ?? null), ['class'=> ($errors->has($name) ? 'form-control is-invalid select2' : 'form-control select2')] + ($form_options ?? [])) !!}
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
    @isset($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endisset
</div>