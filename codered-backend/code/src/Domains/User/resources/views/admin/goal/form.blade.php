<div class="panel-body row">

	<div class="col-12"></div>
	@include('admin.components.inputs.text', ['name' => 'name', 'label' => trans('course::lang.name'), 'form_options'=> ['required']])
	
	@include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("course::lang.Activation"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Activate", "0" => "Suspend" ]])
	
	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])
	
</div>

