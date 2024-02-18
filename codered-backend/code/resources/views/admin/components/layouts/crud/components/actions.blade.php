{!! Form::open(['method' => 'DELETE', 'url' => [$route, $id], 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $id) !!}

@if(isset($permitted_actions) && (@$permitted_actions['edit'] == null || is_permitted(@$permitted_actions['edit'])))
	<a data-toggle="tooltip" data-placement="top" title="Edit" href="{{ url("{$route}/{$id}/edit") }}" class="btn btn-icon btn-sm mr-1">
		<i class="fas fa-pen"></i>
	</a>
@endif

@if(isset($showable) && ($showable == true || $showable == 1))
	@if(isset($permitted_actions) && (@$permitted_actions['show'] == null || is_permitted(@$permitted_actions['show'])))
		<a data-toggle="tooltip" data-placement="top" title="View" href="{{ url("{$route}/{$id}") }}" class="btn btn-icon btn-sm mr-1">
			<i class="fas fa-eye"></i>
		</a>
	@endif
@endif

@if(isset($permitted_actions) && (@$permitted_actions['delete'] == null || is_permitted(@$permitted_actions['delete'])))
	<button data-toggle="tooltip" data-placement="top" title="Delete" type="submit"  class="btn btn-icon btn-sm mr-1"
		onclick="return confirm(' Important Note: If user want to delete added by enterprise with license the license will be back to the enterprise, Confirm Delete operation ?');">
		<i class="fas fa-trash"></i>
	</button>
@endif

{!! Form::close() !!}
