{!! Form::hidden('id', $id) !!}
@if(isset($permitted_actions) && ($permitted_actions['show'] == null || is_permitted($permitted_actions['show'])))
<a data-toggle="tooltip" data-placement="top" title="View" href="{{ url("admin/application-project/{$id}") }}" class="btn btn-icon btn-sm mr-1">
    <i class="fas fa-eye"></i>
</a>
@endif


{!! Form::close() !!}
