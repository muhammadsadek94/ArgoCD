<div class="panel-body row">

	<div class="col-12"></div>
	@include('admin.components.inputs.text', ['name' => 'name', 'label' => trans('course::lang.name'), 'form_options'=> ['required']])
	
	@include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("user::lang.status"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Active", "0" => "Suspended" ]])
	
	
	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])
	
</div>

