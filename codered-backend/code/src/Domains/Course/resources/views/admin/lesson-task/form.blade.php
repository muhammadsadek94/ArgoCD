<div class="panel-body row">

    @include('admin.components.inputs.text', [
        'name' => 'title',
        'label' => trans('course::lang.title'),
        'form_options'=> [
          'required'
        ],
        'cols' => 'col-6'
    ])

    @include('admin.components.inputs.textarea', [
        'name' => 'description',
        'label' => trans('course::lang.description'),
        'form_options'=> [
          'required',
          'rows' => 2
        ],
        'cols' => 'col-6'
    ])

	@include('admin.components.inputs.select', [
        'name' => 'activation',
        'label' => trans("course::lang.status"),
        'form_options'=> ['required'],
        'select_options' =>  [
            0      => 'Deactivated',
            1      => 'Active',
        ],
    ])

	@include('admin.components.inputs.number', [
        'name' => 'sort',
        'label' => trans("Sort"),
        'form_options'=> ['required', 'min' => 1],
    ])


	{!! Form::hidden('course_id', request('course_id')) !!}
	{!! Form::hidden('chapter_id', request('chapter_id')) !!}
	{!! Form::hidden('lesson_id', request('lesson_id')) !!}


	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>



