<div class="panel-body row">
	@include('admin.components.inputs.image', [
        'name' => 'image_id',
		'label' => trans('user::lang.image'),
		'cols' => 'col-lg-4 offset-lg-4 col-12 ',
		'form_options'=> ['required'],
		'value' => $row->image_id ?? null,
		'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null])
		
	<div class="col-12"></div>
	@include('admin.components.inputs.text', ['name' => 'title', 'label' => trans('Title'), 'form_options'=> ['required']])
	@include('admin.components.inputs.select', ['name' => 'user_id', 'label' => trans("Instructor"), 'form_options'=> ['required', 'placeholder' => 'Choose Instructor'], 'select_options' =>  $users])
	@include('admin.components.inputs.date', ['name' => 'date', 'label' => trans('Date'), 'form_options'=> ['required']])
	@include('admin.components.inputs.time', ['name' => 'time', 'value' => (isset($row)) ? $row->time : null ,'label' => trans('Time'), 'form_options'=> ['required']])
	@include('admin.components.inputs.url', ['name' => 'link', 'label' => trans('Link'), 'form_options'=> ['required']])

	@include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("Activation"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Activate", "0" => "Suspend" ]])
	@include('admin.components.inputs.select', ['name' => 'type', 'label' => 'Type', 'form_options'=> ['required'], 'select_options' =>  ["2" => "Recorded", "1" => "Upcoming" ]])

	@include('admin.components.inputs.textarea', [
        'name' => 'description',
        'label' => 'Workshop Description',
        'form_options' => [
            'required',
        ],
        'cols' => 'col-12'
    ])
	
	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])
	
</div>

