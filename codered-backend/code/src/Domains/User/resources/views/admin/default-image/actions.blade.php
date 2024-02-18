{!! Form::open(['method' => 'DELETE', 'url' => [$route, $id], 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $id) !!}

	<a data-toggle="tooltip" data-placement="top" title="View" href="{{ url("{$route}/{$id}") }}" class="btn btn-icon btn-sm mr-1">
		<i class="fas fa-eye"></i>
	</a>


{!! Form::close() !!}
