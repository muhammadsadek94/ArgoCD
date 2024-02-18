<div class="panel-body row">


    @include('admin.components.inputs.image', [
        'name' => 'image_id',
        'label' => 'Image',
        'cols' => 'col-lg-4 offset-lg-4 col-12',
        'value' => $row->image_id ?? null,
        'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
        'endpoint' => url(Constants::ADMIN_BASE_URL . '/brand/actions/upload-image'),
        'form_options' => ['required'],
    ])

    <div class="col-12"></div>
    @include('admin.components.inputs.text', [
        'name' => 'alt_text',
        'label' => 'Name',
        'form_options' => ['required'],
    ])




    {{-- @include('admin.components.inputs.select', ['name' => 'app_type', 'label' => trans("project::lang.app_type"), 'form_options'=> ['required'], 'select_options' =>  ["1" => trans('project::lang.user'), "2" => trans('project::lang.provider') ]]) --}}
    {{-- {!! Form::hidden('app_type', App\Domains\project\Enum\AppTypes::USER_APP, ['id' => 'id']) !!} --}}
    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])

</div>
