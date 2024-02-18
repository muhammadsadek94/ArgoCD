<div class="panel-body row">
    @include('admin.components.inputs.textarea', [
        'name' => 'objective_text',
        'label' => trans('course::lang.objective_text'),
        'form_options'=> [
          'required',
          'rows' => 2
        ],
        'cols' => 'col-12'
    ])


    @include('admin.components.inputs.number', [
        'name' => 'sort',
        'label' => trans("Sort"),
        'form_options'=> ['required', 'min' => 1],
        'cols' => 'col-12'
    ])
    {!! Form::hidden('course_id', request('course_id')) !!}
    {!! Form::hidden('chapter_id', request('chapter_id')) !!}
    {!! Form::hidden('lesson_id', request('lesson_id')) !!}

	{!! Form::hidden('lesson_id', $lesson->id) !!}
</div>
