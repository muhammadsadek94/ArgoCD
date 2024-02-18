<div class="panel-body row">




	@include('admin.components.inputs.text', [
        'name' => 'username',
        'label' => trans('Username'),
        'form_options'=> [
          'required'
        ],
        'cols' => 'col-12 col-md-6'
    ])


@include('admin.components.inputs.password', [
        'name' => 'password',
        'label' => trans('password'),
        'form_options'=> [
          isset($row) ? null : 'required'
        ],
        'cols' => 'col-12 col-md-6'
    ])




	@include('admin.components.inputs.select', [
        'name' => 'activation',
        'label' => trans("course::lang.status"),
        'form_options'=> ['required'],
        'select_options' =>  $activation_list,
        'cols' => 'col-12 col-md-3'
    ])




	@include('admin.components.inputs.select', [
		'name' => 'course_ids[]',
		'label' => trans("Microdegrees"),
		'form_options'=> ['required', 'multiple'],
		'select_options' =>  $courses_list,
		'value' =>  isset($row) ? $row->course_ids : null,
        'cols' => 'col-12 col-md-6'

	])



	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>


@push('script')
	<script>
        $(document).ready(function () {


            $('select[name="course_ids[]"]').select2({
                'multiple': true,
                'tags': true

            });

        });
	</script>
@endpush

