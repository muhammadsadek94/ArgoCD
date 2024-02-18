<div class="form-group {{ $cols ?? 'col-lg-6 col-12' }}  {{ $errors->has($name) ? ' has-danger' : '' }}">
    {!! Form::hidden($name, 0) !!}
    <div class="custom-control custom-checkbox">
        {!! Form::checkbox($name, $value ?? null, $isChecked ?? null,  array_merge(['id'=>$name, 'class'=> ($errors->has($name) ? 'custom-control-input is-invalid' : 'custom-control-input')], $form_options ?? [])) !!}
        <label class="custom-control-label" for="{{ $name }}">{{ $label }}</label>
    </div>
    @error($name)
    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
    @isset($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endisset
</div>


