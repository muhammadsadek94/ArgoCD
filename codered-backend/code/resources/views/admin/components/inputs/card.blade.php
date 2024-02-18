    <div class="card card-custom {{ $bg_color }} card-stretch gutter-b">

        <div class="card-body">
            <div class="float-left">
                <span class="card-title font-weight-bolder {{ $text_color }} font-size-h2 mb-0 mt-6 d-block">{{ $value }}</span>
                <span class="font-weight-bold {{ $text_color }} font-size-sm">{{ $label }}</span>
            </div>
            <div class="float-right pt-1">
                <i class="rounded-circle {{ $icon_class }} {{ isset($padding) ? $padding : 'p-2' }} {{ isset($size)? $size : 'fa-2x' }}"></i>
            </div>
        </div>

    </div>


@push('head')
    <style>
        .bg-darkblue{background: #405A7B}
        .darkblue{color: #405A7B}
        .bg-red{background: #ef5562}
        .red{color: #ef5562}
        .green{color: #53e69d}
        .bg-green{background: #53e69d}
        .blue{color: #2cabe3}
        .bg-blue{background: #2cabe3}
        .border-white-2{
            border: 1px solid #e2e2e2;
        }

    </style>
@endpush
