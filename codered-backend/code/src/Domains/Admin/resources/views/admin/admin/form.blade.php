<div class="panel-body row">
	@include('admin.components.inputs.image', [
    	'name' => 'image_id',
    	'label' => trans('admin::lang.image'),
		'cols' => 'col-lg-4 offset-lg-4 col-12 ',
		'value' => $row->image_id ?? null,
		'endpoint' => url(Constants::ADMIN_BASE_URL.'/admin/action/upload-profile-picture'),
		'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null
	])
	<div class="col-12"></div>
	@include('admin.components.inputs.text', ['name' => 'name', 'label' => trans('admin::lang.name'), 'form_options'=> ['required']])
	@include('admin.components.inputs.email', ['name' => 'email', 'label' => trans('admin::lang.email'), 'form_options'=> ['required']])
    @include('admin.components.inputs.text', ['name' => 'phone', 'label' => trans('admin::lang.phone'), 'extra_options'=> ['required', 'placeholder' => 'EX: 919150000000']])


	@include('admin.components.inputs.select', ['name' => 'role_id', 'label' => trans("admin::lang.role"), 'form_options'=> ['required'], 'select_options' =>  $roles_list])

	@include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("admin::lang.status"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Active", "0" => "Suspended" ]])
	<div class="col-12"></div>

	@if(!isset($row))
		@include('admin.components.inputs.password', ['name' => 'password', 'label' => trans('admin::lang.password'), 'form_options' => [(isset($row) ? '' : 'required')]])
	@endif

	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>




@isset($row)
	@push('form_section')
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body">
						{!! Form:: model($row,['method'=>'PATCH','url' => ["{$route}/action", $row->id, "password"], 'files'=>true,'data-toggle'=> 'ajax']) !!}
						<div class="panel-body row">
							@include('admin.components.inputs.password', ['name' => 'password', 'label' => trans('admin::lang.password'), 'form_options' => [(isset($row) ? '' : 'required')]])
							@include('admin.components.inputs.success-btn', ['button_text' => trans('admin::lang.update_password'), 'button_extra_class' => 'float-right'])
						</div>
						{!! Form::close() !!}
					</div> <!-- end card-body-->
				</div> <!-- end card-->
			</div> <!-- end col-->
		</div>
	@endpush
@endisset

