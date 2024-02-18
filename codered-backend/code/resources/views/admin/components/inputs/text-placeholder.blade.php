<div class="form-group {{ $cols ?? 'col-lg-6 col-12' }} {{ $errors->has($name) ? ' has-danger' : '' }}">
    @php
        $isMandatory = in_array('required', $form_options ?? []) ? 'mandatory' : '';
    @endphp

    @php
    if(isset($search_placeholder) && $name == 'search') {
        if(isset($form_options) && count($form_options)) {
            $form_options['placeholder'] = \Str::limit($search_placeholder, 32);
        } else {
            $form_options = [];
            $form_options['placeholder'] = \Str::limit($search_placeholder, 32);
        }
    } else {
        if(isset($form_options) && count($form_options)) {
            $form_options['placeholder'] = \Str::limit($label, 32);
        } else {
            $form_options = [];
            $form_options['placeholder'] = \Str::limit($label, 32);
        }
    }
    @endphp
    {!! Form::text($name, $value ?? null, ['class'=> ($errors->has($name) ? 'form-control search-custom-input is-invalid' : 'form-control search-custom-input')] + ($form_options ?? [])) !!}
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
    @isset($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endisset
</div>
