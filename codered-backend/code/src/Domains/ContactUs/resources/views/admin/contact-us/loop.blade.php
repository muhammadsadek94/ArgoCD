@foreach ($rows as $row)
    <tr>
        <td>{{ $row->first_name . ' '. $row->last_name  }}</td>
        <td>
            @if($row->status == 0)
                <span class="badge badge-danger">@lang("contact_us::lang.new")</span>
            @elseif($row->status == 1)
                <span class="badge badge-info">@lang("contact_us::lang.seen")</span>
            @else
                <span class="badge badge-success">@lang("contact_us::lang.replied")</span>
            @endif
        </td>
        <td>{{ $row->email }}</td>
        @include("{$view_path}.actions")
    </tr>
@endforeach
