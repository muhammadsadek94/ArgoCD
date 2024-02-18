{!! Form::open(['method' => 'DELETE', 'url' => "{$route}/{$id}?course_id={$id}&chapter_id={$row->chapter_id}", 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $id) !!}

@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['edit'])))
	<a class="btn btn-icon btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Edit"
       href="{{ url("{$route}/{$id}/edit?course_id={$row->course_id}&chapter_id={$row->chapter_id}") }}"> <i
				class="fa fa-pen"></i></a>
@endif

@if(isset($showable) && ($showable == true || $showable == 1))
	@if(isset($permitted_actions) && ($permitted_actions['show'] == null || is_permitted($permitted_actions['show'])))
		<a class="btn btn-icon btn-sm mr-1 "  data-toggle="tooltip" data-placement="top" title="Delete"
           href="{{ url("{$route}/{$id}") }}"> <i class="fa fa-eye"></i> @lang('View')</a>
	@endif
@endif


@if(isset($permitted_actions) && ($permitted_actions['delete'] == null || is_permitted($permitted_actions['delete'])))
	<button type="submit"
            data-toggle="tooltip" data-placement="top" title="Delete"
            class="btn btn-icon btn-sm mr-1"
			onclick="return confirm('Confirm Delete operation ?');">
		<i class="fa fa-trash"></i>
	</button>
@endif

@if(in_array($row->type, [\App\Domains\Course\Enum\LessonType::LAB, \App\Domains\Course\Enum\LessonType::CYPER_Q]))

{{--    <a class="btn btn-primary " style="background-color: #3DC47E;border-radius: 6px;border-color:#3DC47E"--}}
{{--       href="{{ url(Constants::ADMIN_BASE_URL . "/lesson-objective?course_id={$row->course_id}&chapter_id={$row->chapter_id}&lesson_id={$row->id}") }}">--}}
{{--        <i class="fa fa-eye"></i> Objectives--}}
{{--    </a>--}}

{{--    <a class="btn btn-secondary" style="background-color: #3DC47E;border-radius: 6px;border-color:#3DC47E"--}}
{{--       href="{{ url(Constants::ADMIN_BASE_URL . "/lesson-task?course_id={$row->course_id}&chapter_id={$row->chapter_id}&lesson_id={$row->id}") }}">--}}
{{--        <i class="fa fa-tasks"></i> Tasks--}}
{{--    </a>--}}
@endif
{!! Form::hidden('course_id', request('course_id')) !!}
{!! Form::hidden('chapter_id', request('chapter_id')) !!}

{!! Form::close() !!}
