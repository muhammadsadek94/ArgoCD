{!! Form::open(['method' => 'DELETE', 'url' => "{$route}/{$id}?course_id={$row->course_id}", 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $id) !!}

@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['edit'])))
	<a class="btn btn-icon btn-sm mr-1 "  data-toggle="tooltip" data-placement="top" title="Edit"
       href="{{ url("{$route}/{$id}/edit?course_id={$row->course_id}") }}"> <i
				class="fa fa-pen"></i></a>
@endif

@if(isset($showable) && ($showable == true || $showable == 1))
	@if(isset($permitted_actions) && ($permitted_actions['show'] == null || is_permitted($permitted_actions['show'])))
		<a class="btn btn-icon btn-sm mr-1 " href="{{ url("{$route}/{$id}") }}"> <i class="fa fa-eye"></i> @lang('lang.show')</a>
	@endif
@endif

<a class="btn btn-icon btn-sm mr-1 "  data-toggle="tooltip" data-placement="top" title="Lessons"
   href="{{ url(Constants::ADMIN_BASE_URL . "/lesson?course_id={$row->course_id}&chapter_id={$row->id}") }}">
	<i class="fa fa-eye"></i>
</a>

<a class="btn btn-icon btn-sm mr-1"   data-toggle="tooltip" data-placement="top" title="Add Lessons"
   href="{{ url(Constants::ADMIN_BASE_URL . "/lesson/create?course_id={$row->course_id}&chapter_id={$row->id}") }}">
	<i class="fa fa-plus"></i>
</a>



@if(isset($permitted_actions) && ($permitted_actions['delete'] == null || is_permitted($permitted_actions['delete'])))
	<button type="submit"
            data-toggle="tooltip" data-placement="top" title="Delete"
			class="btn btn-icon btn-sm mr-1"
			onclick="return confirm('Confirm Delete operation ?');">
		<i class="fa fa-trash"></i>
	</button>
@endif

{!! Form::close() !!}
