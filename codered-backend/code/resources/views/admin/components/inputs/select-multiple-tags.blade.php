<div class="form-group {{ $cols ?? 'col-lg-6 col-12' }} {{ $errors->has($name) ? ' has-danger' : '' }}">
    @php
        $isMandatory = in_array('required', $form_options ?? []) ? 'mandatory' : '';
    @endphp

    {!! Form::label($name, $label, ['class' => "form-control-label {$isMandatory}"]) !!}
    <select name="{{ $name }}" id=""
        class="{{ $errors->has($name) ? 'form-control is-invalid select2-multiple' : 'form-control select2-multiple' }}"
        multiple="multiple">
        @foreach ($select_options as $option)
            <option value="{{ $option }}" selected>{{ $option }}</option>
        @endforeach
    </select>

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
        $(".select2-multiple").select2({
            tags: true,
            tokenSeparators: [',']
        })
    </script>
@endpush
