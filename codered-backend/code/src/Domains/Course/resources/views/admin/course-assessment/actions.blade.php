{!! Form::open(['method' => 'DELETE', 'url' => "{$route}/{$id}?course_id={$row->course_id}", 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $id) !!}

{{--@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['edit'])))--}}
{{--	<a class="btn btn-info " href="{{ url("{$route}/{$id}/edit?course_id={$row->course_id}") }}"> <i--}}
{{--				class="fa fa-pen"></i> @lang('lang.edit')</a>--}}
{{--@endif--}}

{{--@if(isset($showable) && ($showable == true || $showable == 1))--}}
{{--	@if(isset($permitted_actions) && ($permitted_actions['show'] == null || is_permitted($permitted_actions['show'])))--}}
{{--		<a class="btn btn-primary " href="{{ url("{$route}/{$id}") }}"> <i class="fa fa-eye"></i> @lang('lang.show')</a>--}}
{{--	@endif--}}
{{--@endif--}}



@if(isset($permitted_actions) && ($permitted_actions['delete'] == null || is_permitted($permitted_actions['delete'])))
	<button type="submit"
			class="btn btn-danger"
			 style="background-color:#E83C30; border-radius: 6px; border-color:#E83C30"
			onclick="return confirm('Confirm Delete operation ?');">
		<i class="fa fa-trash"></i> @lang('lang.delete')
	</button>
@endif

{!! Form::close() !!}
