@push('head')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.min.css"
        integrity="sha512-m/uSzCYYP5f55d4nUi9mnY9m49I8T+GUEe4OQd3fYTpFU9CIaPazUG/f8yUkY0EWlXBJnpsA7IToT2ljMgB87Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush


<div class="panel-body row">

    @include('admin.components.inputs.image', [
        'name' => 'image_id',
        'label' => 'Image',
        'cols' => 'col-lg-4 offset-lg-4 col-12',
        'value' => $row->image_id ?? null,
        'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
        'endpoint' => url(Constants::ADMIN_BASE_URL . '/slider/actions/upload-image'),
        'form_options' => ['required'],
    ])


    <div class="col-12"></div>
    @include('admin.components.inputs.text', [
        'name' => 'title',
        'label' => 'Title',
        'form_options' => ['required'],
    ])


    <div class="form-group  col-lg-6 col-12 {{ $errors->has('title_color') ? ' has-danger' : '' }}">

        {!! Form::label('title_color', 'title color', ['class' => 'form-control-label mandatory']) !!}
        {!! Form::text('title_color', $value ?? null, ['class' => $errors->has('title_color') ? 'form-control is-invalid' : 'form-control simple-color-picker']) !!}
        @error('title_color')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        @isset($help)
            <small class="form-text text-muted">{{ $help }}</small>
        @endisset


    </div>

    @include('admin.components.inputs.text', [
        'name' => 'sub_title',
        'label' => ' Sub Title',
        'form_options' => ['required'],
    ])

    <div class="form-group  col-lg-6 col-12 {{ $errors->has('sub_title_color') ? ' has-danger' : '' }}">

        {!! Form::label('sub_title_color', 'sub title color', ['class' => 'form-control-label mandatory']) !!}
        {!! Form::text('sub_title_color', $value ?? null, ['class' => $errors->has('sub_title_color') ? 'form-control is-invalid' : 'form-control simple-color-picker']) !!}
        @error('sub_title_color')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        @isset($help)
            <small class="form-text text-muted">{{ $help }}</small>
        @endisset


    </div>

    @include('admin.components.inputs.select', [
        'name' => 'brands[]',
        'label' => 'Tools',
        'form_options' => ['multiple'],
        'select_options' => $brands,
        'cols' => 'col-12',
    ])

    @include('admin.components.inputs.textarea', [
        'name' => 'description',
        'label' => 'Description',
        'form_options' => ['required'],
        'cols' => 'col-12 col-md-6',
    ])



    <div class="form-group  col-lg-6 col-12 {{ $errors->has('description_color') ? ' has-danger' : '' }}">

        {!! Form::label('description_color', 'description color', ['class' => 'form-control-label mandatory']) !!}
        {!! Form::text('description_color', $value ?? null, ['class' => $errors->has('description_color') ? 'form-control is-invalid' : 'form-control simple-color-picker']) !!}
        @error('description_color')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        @isset($help)
            <small class="form-text text-muted">{{ $help }}</small>
        @endisset


    </div>


    <div class="col-12"></div>
    @include('admin.components.inputs.text', [
        'name' => 'button_txt',
        'label' => ' Button Text',
        'form_options' => ['required'],
    ])

    @include('admin.components.inputs.text', [
        'name' => 'button_target_url',
        'label' => ' Button Target URL',
        'form_options' => ['required'],
    ])


    {{-- @include('admin.components.inputs.select', ['name' => 'app_type', 'label' => trans("project::lang.app_type"), 'form_options'=> ['required'], 'select_options' =>  ["1" => trans('project::lang.user'), "2" => trans('project::lang.provider') ]]) --}}
    {{-- {!! Form::hidden('app_type', App\Domains\project\Enum\AppTypes::USER_APP, ['id' => 'id']) !!} --}}
    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])

</div>

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js"
        integrity="sha512-94dgCw8xWrVcgkmOc2fwKjO4dqy/X3q7IjFru6MHJKeaAzCvhkVtOS6S+co+RbcZvvPBngLzuVMApmxkuWZGwQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function() {
            $('.simple-color-picker').colorpicker();
        });
    </script>
@endpush
