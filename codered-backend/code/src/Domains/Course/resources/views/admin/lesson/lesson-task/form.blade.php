<div class="panel-body row">
    @include('admin.components.inputs.text', [
         'name' => 'title',
         'label' => trans('course::lang.title'),
         'form_options'=> [
           'required'
         ],
      'cols' => 'col-12'
     ])

    @include('admin.components.inputs.textarea', [
        'name' => 'description',
        'label' => trans('course::lang.description'),
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
    {!! Form::hidden('lesson_id', $lesson->id) !!}
</div>
