<div class="panel-body row">
    <div class="col-12"></div>

    @include('admin.components.inputs.text', ['name' => 'name_en', 'label' => trans('geography::lang.name_en'), 'form_options'=> ['required']])
    @include('admin.components.inputs.text', ['name' => 'name_ar', 'label' => trans('geography::lang.name_ar'), 'form_options'=> ['required']])

    @include('admin.components.inputs.select', ['name' => 'city_id', 'label' => trans("geography::lang.city"), 'form_options'=> ['required'], 'select_options' =>  $cities_list])
    @include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("geography::lang.activation"), 'form_options'=> ['required'], 'select_options' =>  ["1" => trans("geography::lang.activate"), "0" => trans("geography::lang.suspend") ]])


    <div class="col-12"></div>

    @include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

    {!! Form::hidden('country_id', request('country')) !!}
</div>
