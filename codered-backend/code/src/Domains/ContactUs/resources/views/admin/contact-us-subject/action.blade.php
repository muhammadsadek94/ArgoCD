{!! Form::open(['method' => 'DELETE', 'url' => ["/$route",$row->id], 'class' => 'form-horizontal']) !!}

        {!! Form::hidden('id', $row->id) !!}
        <a class="btn btn-info btn-flat" href="{{ url("/$route/{$row->id}/edit/") }}"> <i class="fa fa-pen"></i> @lang('lang.edit')</a>
        <button type="submit" class="btn btn-danger btn-flat" onclick="return confirm('Confirm Delete operation ?');"> <i class="fa fa-trash"></i> @lang('lang.delete')</button>

{!! Form::close() !!}
