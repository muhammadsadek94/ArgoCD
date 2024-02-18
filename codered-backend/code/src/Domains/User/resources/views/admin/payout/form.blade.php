<div class="panel-body row">

    @if (empty($row))
        <div class="col-12"></div>

        @include('admin.components.inputs.select', [
            'name' => 'type',
            'label' => 'Type',
            'form_options' => ['placeholder' => 'Select Type'],
            'select_options' => [
                1 => 'Quarter',
                2 => 'Date',
            ],
        ])

        <div id="quarter_div" class="d-none col-12 px-0">

            @include('admin.components.inputs.number', [
                'name' => 'year',
                'label' => trans('Year'),
                'form_options' => [''],
                'value' => date('Y'),
            ])

            @include('admin.components.inputs.select', [
                'name' => 'quarter',
                'label' => trans('Quarter'),
                'form_options' => ['placeholder' => 'Select Quarter'],
                'select_options' => [
                    1 => 'Quarter 1 [01/01 - 03/31]',
                    2 => 'Quarter 2 [04/01 - 06/30]',
                    3 => 'Quarter 3 [07/01 - 09/30]',
                    4 => 'Quarter 4 [10/01 - 12/31]',
                ],
            ])

        </div>

        <div id="date_div" class="d-none col-12 px-0">

            @include('admin.components.inputs.date', [
                'name' => 'start_date',
                'label' => 'Start Date',
                'form_options' => [''],
            ])

            @include('admin.components.inputs.date', [
                'name' => 'end_date',
                'label' => 'End Date',
                'form_options' => [''],
            ])

        </div>

        @include('admin.components.inputs.number', [
            'name' => 'royalty',
            'label' => trans('Total Royalty'),
            'form_options' => ['required', 'step' => 'any'],
        ])
        @include('admin.components.inputs.number', [
            'name' => 'royalties_carried_out',
            'label' => trans('Royalties Carried Out From Last Quarter'),
            'form_options' => ['step' => 'any'],
        ])
        @include('admin.components.inputs.number', [
            'name' => 'outstanding_advances',
            'label' => trans('Outstanding Advances'),
            'form_options' => ['step' => 'any'],
        ])

        @include('admin.components.inputs.select', [
            'name' => 'course_id',
            'label' => 'Course',
            'form_options' => ['required'],
            'select_options' => $courses,
            'cols' => 'col-12 col-md-6 ',
        ])

        {{--    href="{{ url($admin_base_url . "/payout/actions/export") }}" --}}


        @include('admin.components.inputs.success-btn', [
            'name' => 'payout',
            'button_text' => $submitButton,
            'button_extra_class' => 'float-right',
        ])
        {{--    <div class="w-100 "> --}}
        {{--        <button id="export" type="submit" name="action" class="btn btn-blue float-right" value="save">Export Report</button> --}}
        {{--    </div> --}}

</div>
@endif
@isset($row)
    {!! Form::model($row, [
        'method' => 'PATCH',
        'url' => ["{$route}/action", $row->id, 'timing'],
        'files' => true,
        'data-stoggle' => 'ajax',
    ]) !!}
    <div class="col-12">
        <h3>{{ $row->course ? $row->course->internal_name : '' }}</h3>
    </div>
    <div class="col-12"></div>

    @include('admin.components.inputs.select', [
        'name' => 'type',
        'label' => 'Type',
        'form_options' => ['placeholder' => 'Select Type'],
        'select_options' => [
            1 => 'Quarter',
            2 => 'Date',
        ],
    ])

    <div id="quarter_div" class="d-none col-12 px-0">

        @include('admin.components.inputs.number', [
            'name' => 'year',
            'label' => trans('Year'),
            'form_options' => [''],
        ])

        @include('admin.components.inputs.select', [
            'name' => 'quarter',
            'label' => trans('Quarter'),
            'form_options' => ['placeholder' => 'Select Quarter'],
            'select_options' => [
                1 => 'Quarter 1 [01/01 - 03/31]',
                2 => 'Quarter 2 [04/01 - 06/30]',
                3 => 'Quarter 3 [07/01 - 09/30]',
                4 => 'Quarter 4 [10/01 - 12/31]',
            ],
        ])

    </div>

    <div id="date_div" class="d-none col-12 px-0">

        @include('admin.components.inputs.date', [
            'name' => 'start_date',
            'label' => 'Start Date',
            'form_options' => [''],
        ])

        @include('admin.components.inputs.date', [
            'name' => 'end_date',
            'label' => 'End Date',
            'form_options' => [''],
        ])

    </div>

    @include('admin.components.inputs.number', [
        'name' => 'royalty',
        'label' => trans('Total Royalty'),
        'form_options' => ['required'],
    ])

    @include('admin.components.inputs.number', [
        'name' => 'royalties_carried_out',
        'label' => trans('Royalties Carried Out From Last Quarter'),
        'form_options' => [''],
    ])

    @include('admin.components.inputs.number', [
        'name' => 'outstanding_advances',
        'label' => trans('Outstanding Advances'),
        'form_options' => [''],
    ])
    @include('admin.components.inputs.success-btn', [
        'name' => 'submit',
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])

    {!! Form::close() !!}
@endisset

@push('script')
    <script>
        $("#export").click(function() {
            var form = $(this).parents('form:first');
            form.attr('action', `{{ url($admin_base_url . '/payout/actions/export') }}`)
        });

        $("#payout").click(function() {
            var form = $(this).parents('form:first');
            form.attr('action', `{{ url($admin_base_url . '/payout') }}`)
            {{-- form.attr('action')=`{{ url($admin_base_url . "/payout") }}` --}}
        });

        $(document).ready(function () {
            var type = $("#type").val();
            if (type == 1) {
                $("#quarter_div").removeClass('d-none').addClass('d-flex');
                $("#date_div").removeClass('d-flex').addClass('d-none');
            }
            if (type == 2) {
                $("#quarter_div").removeClass('d-flex').addClass('d-none');
                $("#date_div").removeClass('d-none').addClass('d-flex');
            }
            if (!type) {
                $("#quarter_div").removeClass('d-flex').addClass('d-none');
                $("#date_div").removeClass('d-flex').addClass('d-none');
            }
        });

        $("#type").change(function() {
            var type = $(this).val();
            if (type == 1) {
                $("#quarter_div").removeClass('d-none').addClass('d-flex');
                $("#date_div").removeClass('d-flex').addClass('d-none');
            }
            if (type == 2) {
                $("#quarter_div").removeClass('d-flex').addClass('d-none');
                $("#date_div").removeClass('d-none').addClass('d-flex');
            }
            if (!type) {
                $("#quarter_div").removeClass('d-flex').addClass('d-none');
                $("#date_div").removeClass('d-flex').addClass('d-none');
            }
        });
    </script>
@endpush
