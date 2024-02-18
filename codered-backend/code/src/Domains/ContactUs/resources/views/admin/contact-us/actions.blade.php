<td>
    {!! Form::open(['method' => 'DELETE', 'url' => [$route, $row->id], 'class' => 'form-horizontal']) !!}
    @if(isset($permitted_actions) && ($permitted_actions['show'] == null || is_permitted($permitted_actions['show'])))
        <a class="btn btn-icon btn-sm mr-1 " data-toggle="tooltip" data-placement="top" title="Reply" href="{{ url("{$route}/{$row->id}") }}">
            <i class="fa fa-eye"></i>
        </a>
    @endif

    @if(isset($permitted_actions) && ($permitted_actions['delete'] == null || is_permitted($permitted_actions['delete'])))
    <button data-toggle="tooltip" data-placement="top" title="Delete"
            type="submit" class="btn btn-icon btn-sm mr-1 " onclick="return confirm('Confirm Delete operation ?');">
        <i class="fa fa-trash"></i>
    </button>
    @endif
    {!! Form::close() !!}
</td>
