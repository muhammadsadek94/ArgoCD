@foreach ($rows as $row)
    <tr>
        @foreach ($select_columns as $column)
            @php
                $key = $column['key'];
            @endphp

            @if ($column['type'] == ColumnTypes::STRING)
                <td>{{ Str::limit($row->$key, 80) }}</td>
            @elseif($column['type'] == ColumnTypes::CALLBACK)
                <td>
                    {!! $column['key']($row) !!}
                </td>
            @elseif($column['type'] == ColumnTypes::IMAGE)
                @if (!empty($row->$key))
                    <td>
                        <img src="{{ url("{$row->$key->full_url}") }}" class="" style="border-radius: 50%"
                            width="100" height="100" alt="">
                    </td>
                @else
                    <td class="text-center">
                        -
                    </td>
                @endif
            @elseif($column['type'] == ColumnTypes::LABEL)
                @php
                    $values = $column['label_values']; // select col array
                    $database_value = $row->$key; // key name
                    $selected_value = $values[$database_value] ?? null;
                @endphp
                <td>
                    @isset($selected_value)
                        <span style="" class="{{ $selected_value['class'] }}">
                            {{ $selected_value['text'] }}
                        </span>
                    @else
                        <span class="badge badge-info">{{ $database_value }}</span>
                    @endisset
                </td>
            @endif
        @endforeach

        <td>
            @includeFirst(
                ["{$view_path}.actions", 'admin.components.layouts.crud.components.actions'],
                ['id' => $row->id, 'showable' => $isShowable ?? false]
            )
        </td>
    </tr>
@endforeach
