@foreach ($rows as $row)
    <tr>
        @foreach ($select_columns as $column)
            <td>{{ $row->$column }}</td>
        @endforeach
        <td>
            @if($row->activation == 1)
                <h5><span class="badge badge-success">@lang("user::lang.Active")</span></h5>
            @else
            <h5><span class="badge badge-danger">@lang("user::lang.suspended")</span></h5>
            @endif
        </td>
        <td>
            @include("$view_path.action")
        </td>
    </tr>
@endforeach
