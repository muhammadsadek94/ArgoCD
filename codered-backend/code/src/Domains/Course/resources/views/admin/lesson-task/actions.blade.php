{!! Form::open(['method' => 'DELETE', 'url' => "{$route}/{$id}?course_id={$row->course_id}", 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $id) !!}

@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['edit'])))
	<a class="btn btn-info " style="background-color: #1781EB;border-radius: 6px;border-color:#1781EB" href="{{ url("{$route}/{$id}/edit?course_id={$row->course_id}&chapter_id={$row->chapter_id}&lesson_id={$row->lesson_id}") }}"> <i
				class="fa fa-pen"></i> @lang('lang.edit')</a>
@endif



@if(isset($permitted_actions) && ($permitted_actions['delete'] == null || is_permitted($permitted_actions['delete'])))
	<button type="submit"
			class="btn btn-danger"
			style="background-color: #E83C30;border-radius: 6px;border-color:#E83C30"
			onclick="return confirm('Confirm Delete operation ?');">
		<i class="fa fa-trash"></i> @lang('lang.delete')
	</button>
@endif

{!! Form::close() !!}
