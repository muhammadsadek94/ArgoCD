<div class="panel-body row">
	@include('admin.components.inputs.text', [
		'name' => 'name',
		'label' => trans('Name'),
		'form_options'=> [
		  'required'
		],
		'cols' => 'col-12'
	])

    @include('admin.components.inputs.select', [
			'name' => 'type',
			'label' => trans("Type"),
			'form_options'=> ['required', 'onchange' => 'changeType(event)'],
			'select_options' =>  [
				\App\Domains\Course\Enum\LessonResourceTypes::FILE => 'File',
				\App\Domains\Course\Enum\LessonResourceTypes::LINK => 'Link',
			],
            'cols' => 'col-12'
		])

    @include('admin.components.inputs.text', [
		'name' => 'link',
		'label' => trans('Link'),
		'form_options'=> [
		  'required',
		  'disabled'
		],
		'cols' => 'col-12 d-none'
	])


	@include('admin.components.inputs.file', [
		'name' => 'attachment_id',
		'label' => trans('Attachment'),
		'form_options'=> [
		  'required',
		],
		'cols' => 'col-12'
	])

	{!! Form::hidden('lesson_id', $lesson->id) !!}
</div>

@push('script')
    <script>
        function changeType(event){
            if(event.target.value == "{{ \App\Domains\Course\Enum\LessonResourceTypes::FILE }}"){
                $('#link').attr('disabled', true).parent().addClass('d-none');
                return $('#attachment_id').attr('disabled', false).parent().removeClass('d-none');
            }
            $('#attachment_id').attr('disabled', true).parent().addClass('d-none');
            return $('#link').attr('disabled', false).parent().removeClass('d-none');
        }
    </script>
@endpush
