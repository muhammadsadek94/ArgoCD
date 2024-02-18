<div class="panel-body row">
    <div class="col-12"></div>
{{--    @include('admin.components.inputs.image', ['name' => 'image', 'label' => trans('geography::lang.flag_image'),'form_options'=> ['required'],'cols' => 'col-lg-4 offset-lg-4 col-12 ', 'value' => $row->image_id ?? null, 'placeholder' => isset($row->image) ? asset("{$row->image->path}") : null])--}}


    @include('admin.components.inputs.text', ['name' => 'name_en', 'label' => trans('geography::lang.name_en'), 'form_options'=> ['required']])
{{--    @include('admin.components.inputs.text', ['name' => 'name_ar', 'label' => trans('geography::lang.name_ar'), 'form_options'=> ['required']])--}}
{{--    @include('admin.components.inputs.text', ['name' => 'iso', 'label' => trans('geography::lang.iso'), 'form_options'=> ['required']])--}}
{{--    @include('admin.components.inputs.text', ['name' => 'phone_code', 'label' => trans('geography::lang.phone_code'), 'form_options'=> ['required']])--}}
{{--    @include('admin.components.inputs.text', ['name' => 'number_allow_digit', 'label' => trans('geography::lang.number_allow_digit'), 'form_options'=> ['required']])--}}
{{--    @include('admin.components.inputs.text', ['name' => 'currency_name_ar', 'label' => trans('geography::lang.currency_name_ar'), 'form_options'=> ['required']])--}}
{{--    @include('admin.components.inputs.text', ['name' => 'currency_name_en', 'label' => trans('geography::lang.currency_name_en'), 'form_options'=> ['required']])--}}
{{--    @include('admin.components.inputs.text', ['name' => 'currency_symbol', 'label' => trans('geography::lang.currency_symbol'), 'form_options'=> ['required']])--}}


    @include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("geography::lang.activation"), 'form_options'=> ['required'], 'select_options' =>  ["1" => trans("geography::lang.activate"), "0" => trans("geography::lang.suspend") ]])


    <div class="col-12"></div>

    @include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

    {!! Form::hidden('type', App\Domains\User\Enum\UserType::USER) !!}
</div>
