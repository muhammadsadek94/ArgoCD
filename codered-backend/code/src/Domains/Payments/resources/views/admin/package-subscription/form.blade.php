@push('head')
    <style>
        .table thead th {
            vertical-align: middle !important;
        }
    </style>
@endpush

<div class="panel-body row">
    @include('admin.components.inputs.text', [
        'name' => 'name',
        'label' => trans('course::lang.name'),
        'form_options' => ['required'],
    ])
    @include('admin.components.inputs.select', [
        'name' => 'activation',
        'label' => trans('Is Published in all website?'),
        'form_options' => ['required'],
        'select_options' => [
            '1' => 'Publish',
            '0' => 'Hide',
        ],
    ])
    @include('admin.components.inputs.text', [
        'name' => 'amount',
        'label' => trans('Amount'),
        'form_options' => ['required'],
    ])

    {!! Form::hidden('access_type', null) !!}

    @include('admin.components.inputs.select', [
        'name' => 'access_type',
        'label' => trans('Access Type'),
        'form_options' => [
            'required',
            'placeholder' => 'Select Access Type',
            isset($row) ? 'disabled' : '',
            'id' => 'access_type_select',
        ],
        'select_options' => [
            \App\Domains\Payments\Enum\AccessType::PRO => 'Pro Subscription',
            \App\Domains\Payments\Enum\AccessType::COURSE_CATEGORY => '(Bundle) Course Category Subscription',
            \App\Domains\Payments\Enum\AccessType::COURSES => '(Bundle)  Custom Bundle',
            \App\Domains\Payments\Enum\AccessType::LEARN_PATH_CAREER => '(Learn path)  Career Path',
            \App\Domains\Payments\Enum\AccessType::LEARN_PATH_SKILL => '(Learn path)  Skill Path',
            \App\Domains\Payments\Enum\AccessType::INDIVIDUAL_COURSE =>
                '(Individual)  Single Course / Microdegree / Certification',
        ],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'is_installment',
        'label' => 'Installment Enabled?',
        'cols' => 'col-lg-6 col-12 d-none is_installment_input',
        'form_options' => [
            'placeholder' => 'Select Yes or No',
            'id' => 'is_installment_select',
            isset($row) ? 'disabled' : '',
        ],
        'select_options' => [
            1 => 'Yes',
            0 => 'No',
        ],
    ])

    @include('admin.components.inputs.number', [
        'name' => 'installment_count',
        'label' => 'Installments Count',
        'cols' => 'col-lg-6 col-12 d-none installment-input',
        'form_options' => ['min' => '0'],
    ])


    @include('admin.components.inputs.number', [
        'name' => 'free_trial_days',
        'label' => trans('Trial Duration in Days'),
        'cols' => 'col-lg-6 col-12',
        'form_options' => [],
    ])

    {{--    \App\Domains\Payments\Enum\AccessType::LEARN_PATH_CERTIFICATE => '(Learn path)  Certificate', --}}

    @include('admin.components.inputs.select', [
        'name' => 'access_permission',
        'label' => trans('Access Permission'),
        'form_options' => [
            'required',
            'placeholder' => 'Select Access Type',
            isset($row) ? 'disabled' : '',
            'id' => 'access_permission_select',
        ],
        'select_options' => [
            \App\Domains\Payments\Enum\AccessPermission::FULL_CONTENT =>
                'Full Access (Videos + Labs + Exam Vouchers)',
            \App\Domains\Payments\Enum\AccessPermission::CONTENT_ONLY => 'Videos Only',
            \App\Domains\Payments\Enum\AccessPermission::CONTENT_WITH_LABS => 'Videos + Labs',
            \App\Domains\Payments\Enum\AccessPermission::CONTENT_WITH_VOUCHERS => 'Videos + Exam Vouchers',
        ],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'categories[]',
        'label' => trans('Categories'),
        'cols' => 'col-lg-6 col-12 d-none categories-input ',
        'form_options' => ['multiple'],
        'select_options' => $categories_list,
        'value' => isset($row) ? json_decode($row->access_id, true) : [],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'courses[]',
        'label' => trans('Courses'),
        'cols' => 'col-lg-6 col-12 d-none courses-input',
        'form_options' => ['multiple'],
        'select_options' => $courses_list,
        'value' => isset($row) ? json_decode($row->access_id, true) : [],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'individual_courses[]',
        'label' => trans('Courses'),
        'cols' => 'col-lg-6 col-12 d-none individual-input',
        'form_options' => ['id' => 'courses-select'],
        'select_options' => $courses_list,
        'value' => isset($row) ? json_decode($row->access_id, true) : [],
    ])


    @include('admin.components.inputs.select', [
        'name' => 'type',
        'label' => trans('Type'),
        'form_options' => [],
        'cols' => 'col-lg-6 col-12 d-none type-input',
        'select_options' => [
            \App\Domains\Payments\Enum\SubscriptionPackageType::MONTHLY => 'Monthly',
            \App\Domains\Payments\Enum\SubscriptionPackageType::ANNUAL => 'Annual',
        ],
    ])

{{-- {{dd('asdf')}} --}}
    @include('admin.components.inputs.number', [
        'name' => 'duration',
        'label' => trans('Durations in Days *'),
        'cols' => 'col-lg-6 col-12 d-none duration-input',
        'form_options' => [],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'learn_path_id',
        'label' => trans('Learn Path / Bundles'),
        'cols' => 'col-lg-6 col-12 learn_path_id hide',
        'form_options' => ['placeholder' => 'Select Learn Path'],
        'select_options' => $learn_paths,
    ])

    @include('admin.components.inputs.text', [
        'name' => 'sku',
        'label' => trans('SKU'),
        'form_options' => ['required'],
    ])


    <div class="col-12 justify-content-center text-center loading"
        style="position: absolute; height: 100%; width: 81%; top: 50%; display: none">
        <img src="{{ asset('admin/images/loading.gif') }}">
    </div>

    @if (!isset($row) || (isset($row) && $row->access_type != \App\Domains\Payments\Enum\AccessType::INDIVIDUAL_COURSE))
        <div class="container mt-4 table-container d-none">
            <h3> Chapters Plan </h3>
            <table id="table" class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Chapter</th>
                        <th scope="col">Appears After Which Installment? <p class="mb-0"><small>(Installment should
                                    be
                                    enabled)</small>
                            <p class="mb-0"><small>(Empty will be considered as 0)</small>
                            </p>
                        </th>
                        <th scope="col">Included In trial? <p class="mb-0"><small>(Trial days duration
                                    should
                                    be more than
                                    0)</small></p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <p>
                ( Note : Exam Voucher should be available with the final payment in the plan. Please select accordingly )
            </p>
        </div>
    @endif

    @if (isset($row) && $row->access_type == \App\Domains\Payments\Enum\AccessType::INDIVIDUAL_COURSE)
        <div class="container mt-4 table-container">
            <h3> Chapters Plan </h3>
            <table id="table" class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Chapter</th>
                        <th scope="col">Appears After Which Installment? <p class="mb-0"><small>(Installment should
                                    be
                                    enabled)</small>
                            <p class="mb-0"><small>(Empty will be considered as 0)</small>
                            </p>
                        </th>
                        <th scope="col">Included In trial? <p class="mb-0"><small>(Trial days duration
                                    should
                                    be more than
                                    0)</small></p>
                        </th>
                    </tr>
                </thead>
                {{-- {{dd($chapter->sort == $row->chapters->count()+1)}} --}}
                <tbody>
                    @foreach ($row->chapters as $chapter)
                        <tr>
                            <th scope="row">{{ $chapter->sort }}</th>
                            <input type="hidden" name="chapters[{{ $chapter->id }}][course_id]"
                                value="{{ $chapter->course_id }}">
                            <td>{{ $chapter->name }}</td>

                            <td>
                                <input type="number" value="{{ $chapter->pivot?->after_installment_number }}"
                                    class="installment_input" name="chapters[{{ $chapter->id }}][installment]">
                            </td>
                            <td>
                                <input type="hidden" name="chapters[{{ $chapter->id }}][is_free_trial]"
                                    value="0">
                                <input type="checkbox" value="1"
                                    {{ $chapter->pivot?->is_free_trial ? 'checked' : '' }} class="free_trial_checkbox"
                                    name="chapters[{{ $chapter->id }}][is_free_trial]">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>( Note : Exam Voucher should be available with the final payment in the plan. Please select accordingly )</p>
        </div>
    @endif


</div>

<div class="d- courses col-12" id="courses">

    @if (!isset($row))
        @include("{$view_path}.partials.create-courses")
    @endif

    @include("{$view_path}.partials.update-courses")
</div>



@include('admin.components.inputs.success-btn', [
    'button_text' => $submitButton,
    'button_extra_class' => 'float-right',
])




@push('script')
    <script>
        $(document).ready(function() {

            const ACCESS_TYPE_PRO = "{{ \App\Domains\Payments\Enum\AccessType::PRO }}";
            const ACCESS_TYPE_COURSES = "{{ \App\Domains\Payments\Enum\AccessType::COURSES }}";
            const ACCESS_TYPE_COURSE_CATEGORY = "{{ \App\Domains\Payments\Enum\AccessType::COURSE_CATEGORY }}";
            const ACCESS_TYPE_INDIVIDUAL_COURSE =
                "{{ \App\Domains\Payments\Enum\AccessType::INDIVIDUAL_COURSE }}";
            const ACCESS_TYPE_LEARN_PATH_SKILL = "{{ \App\Domains\Payments\Enum\AccessType::LEARN_PATH_SKILL }}";
            const ACCESS_TYPE_LEARN_PATH_CAREER =
                "{{ \App\Domains\Payments\Enum\AccessType::LEARN_PATH_CAREER }}";
            const ACCESS_TYPE_LEARN_PATH_CERTIFICATE =
                "{{ \App\Domains\Payments\Enum\AccessType::LEARN_PATH_CERTIFICATE }}";

            function setupFormInputs() {

                let val = $('#access_type_select').val();

                if (!val) {
                    $('.courses').addClass('d-none');
                    $('.table-container').addClass('d-none');
                }

                if (val == ACCESS_TYPE_PRO) {
                    $('.categories-input').addClass('d-none');
                    $('.duration-input').addClass('d-none');
                    $('.courses-input').addClass('d-none');
                    $('.individual-input').addClass('d-none');
                    $('.type-input').removeClass('d-none');
                    $('.courses').addClass('d-none');
                    $('.learn_path_id').addClass('d-none');
                    $('.table-container').addClass('d-none');
                    $('#table tbody').empty();

                    $('[name="categories[]"]').prop('required', false);
                    $('[name="courses[]"]').prop('required', false);
                    $('[name="individual_courses[]"]').prop('required', false);
                    $('[name="duration"]').prop('required', false);
                    $('[name="is_installment"]').prop('required', false);
                    $('.is_installment_input').addClass('d-none');

                    $('[name="type"]').prop('required', true);


                } else if (val == ACCESS_TYPE_COURSES) {
                    $('.categories-input').addClass('d-none');
                    $('.type-input').addClass('d-none');
                    $('.duration-input').removeClass('d-none');
                    $('.courses-input').removeClass('d-none');
                    $('.individual-input').addClass('d-none');
                    $('.courses').addClass('d-none');
                    $('.learn_path_id').removeClass('d-none');
                    $('.type-input').addClass('d-none');
                    $('.table-container').addClass('d-none');
                    $('#table tbody').empty();
                    $('[name="is_installment"]').prop('required', false);
                    $('.is_installment_input').addClass('d-none');

                    $('[name="categories[]"]').prop('required', false);
                    $('[name="type"]').prop('required', false);
                    $('[name="individual_courses[]"]').prop('required', false);

                    $('[name="duration"]').prop('required', true);
                    $('[name="courses[]"]').prop('required', true);
                } else if (val == ACCESS_TYPE_COURSE_CATEGORY) {
                    $('.duration-input').addClass('d-none');
                    $('.courses-input').addClass('d-none');
                    $('.categories-input').removeClass('d-none');
                    $('.type-input').removeClass('d-none');
                    $('.individual-input').addClass('d-none');
                    $('.courses').addClass('d-none');
                    $('.learn_path_id').removeClass('d-none');
                    $('.table-container').addClass('d-none');
                    $('#table tbody').empty();
                    $('[name="is_installment"]').prop('required', false);
                    $('.is_installment_input').addClass('d-none');

                    $('[name="courses[]"]').prop('required', false);
                    $('[name="duration"]').prop('required', false);
                    $('[name="individual_courses[]"]').prop('required', false);


                    $('[name="type"]').prop('required', true);
                    $('[name="categories[]"]').prop('required', true);
                } else if (val == ACCESS_TYPE_INDIVIDUAL_COURSE) {
                    $('.courses-input').addClass('d-none');
                    $('.categories-input').addClass('d-none');
                    $('.type-input').addClass('d-none');
                    $('.individual-input').removeClass('d-none');
                    $('.duration-input').removeClass('d-none');
                    $('[name="is_installment"]').prop('required', true);
                    $('.is_installment_input').removeClass('d-none');

                    @if (!isset($row))
                        fetchCourseChapters();
                    @endif

                    @if (isset($row) &&
                            $row->access_type == \App\Domains\Payments\Enum\AccessType::INDIVIDUAL_COURSE &&
                            !$row->chapters?->count())
                        fetchCourseChapters();
                    @endif

                    $('[name="courses[]"]').prop('required', false);
                    $('[name="duration"]').prop('required', true);
                    $('[name="individual_courses[]"]').prop('required', true);
                    $('.courses').addClass('d-none');

                    $('.learn_path_id').addClass('d-none');

                    $('[name="type"]').prop('required', false);
                    $('[name="categories[]"]').prop('required', false);
                } else if (val == ACCESS_TYPE_LEARN_PATH_SKILL || val == ACCESS_TYPE_LEARN_PATH_CAREER || val ==
                    ACCESS_TYPE_LEARN_PATH_CERTIFICATE) {

                    $('.courses-input').addClass('d-none');
                    $('.categories-input').addClass('d-none');
                    $('.type-input').addClass('d-none');
                    $('.individual-input').addClass('d-none');
                    $('[name="is_installment"]').prop('required', false);
                    $('.is_installment_input').addClass('d-none');

                    $('.table-container').addClass('d-none');
                    $('#table tbody').empty();

                    $('.duration-input').removeClass('d-none');
                    $('.courses').removeClass('d-none');
                    $('.learn_path_id').removeClass('d-none');

                    $('[name="courses[]"]').prop('required', false);
                    $('[name="duration"]').prop('required', true);
                    $('[name="individual_courses[]"]').prop('required', true);


                    $('[name="type"]').prop('required', false);
                    $('[name="categories[]"]').prop('required', false);

                }

                @if (!isset($row))
                    $('[name="duration"]').val(0);
                    $('[name="categories[]"]').select2().select2('val', '0');
                    $('[name="courses[]"]').select2().select2('val', '0');
                @endif
            }

            $('[name="access_type"]').on('change', setupFormInputs);
            $('[name="access_type"]').on('change', function() {
                let val = $(this).val();
                if (val == ACCESS_TYPE_INDIVIDUAL_COURSE) {
                    fetchCourseChapters();
                }
            });

            setupFormInputs();

            var is_installment_select = $('#is_installment_select');

            is_installment_select.on('change', function() {
                if (is_installment_select.val() == 1) {
                    $('.installment-input').removeClass('d-none');
                } else {
                    $('.installment-input').addClass('d-none');
                }
            });

            if (is_installment_select.val() == 1) {
                $('.installment-input').removeClass('d-none');
            } else {
                $('.installment-input').addClass('d-none');
            }

            $('#free_trial_days').on('keyup', checkFreeTrialDaysInputs);

            $('#is_installment_select').on('change', checkIsInstallmentValue);

            checkFreeTrialDaysInputs();

            checkIsInstallmentValue();

            function checkFreeTrialDaysInputs() {
                let value = $('#free_trial_days').val();
                if (value <= 0) {
                    $('.free_trial_checkbox').prop('disabled', 'disabled');
                    $('.free_trial_checkbox').prop('checked', false);
                } else {
                    $('.free_trial_checkbox').prop('disabled', false);
                }
            }

            function checkIsInstallmentValue() {
                let value = $('#is_installment_select').val();
                if (value <= 0) {
                    $('.installment_input').prop('disabled', 'disabled');
                    $('.installment_input').val(0);
                } else {
                    $('.installment_input').prop('disabled', false);
                }
            }

            $('#courses-select').on('change', fetchCourseChapters);

            function fetchCourseChapters() {
                let course_id = $('#courses-select').val();
                if (!course_id) return;
                $('.loading').show();
                var data = {
                    course_id: course_id
                }

                $('.table-container').addClass('d-none');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': window.app_variables.csrfToken
                    },
                    url: "/admin/fetch-course-chapters",
                    type: "POST",
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function(response) {
                        var i = 1;
                        $('.loading').hide();
                        $('.table-container').removeClass('d-none');
                        $('#table tbody').empty();
                        document.getElementById("table").innerHtml = '';
                        response.data.map((chapter, index) => {
                            $('#table tbody').append(`
                                <tr>
                                    <th scope="row">${chapter.sort}</th>
                                    <input type="hidden" name="chapters[${chapter.id}][course_id]" value="${chapter.course_id}">
                                    <td>${chapter.name}</td>
                                    <td>
                                        <input type="number" class="installment_input" name="chapters[${chapter.id}][installment]">
                                    </td>
                                    <td>
                                        <input type="hidden" name="chapters[${chapter.id}][is_free_trial]" value="0">
                                        <input type="checkbox" value="1" class="free_trial_checkbox" name="chapters[${chapter.id}][is_free_trial]">
                                    </td>
                                </tr>`);
                        });
                        checkFreeTrialDaysInputs();
                        checkIsInstallmentValue();
                    },
                });
            }


        });
    </script>
@endpush
