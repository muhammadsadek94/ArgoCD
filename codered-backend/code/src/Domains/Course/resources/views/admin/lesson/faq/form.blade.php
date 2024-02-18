<div class="panel-body row">
	
	
	@include('admin.components.inputs.text', [
		'name' => 'question',
		'label' => trans('Question'),
		'form_options'=> [
		  'required'
		],
		'cols' => 'col-12'
	])
	
	@include('admin.components.inputs.textarea', [
		'name' => 'answer',
		'label' => trans('Answer'),
		'form_options'=> [
		  'required',
		  'rows' => 2
		],
		'cols' => 'col-12'
	])
	
	{!! Form::hidden('lesson_id', $lesson->id) !!}
</div>