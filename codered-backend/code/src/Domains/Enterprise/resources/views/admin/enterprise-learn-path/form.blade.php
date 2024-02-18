<div class="panel-body row">
    @include('admin.components.inputs.text', ['name' => 'name', 'label' => trans('course::lang.name'), 'form_options'=> ['required']])

    {{--    @include('admin.components.inputs.select', [--}}
    {{--    	'name' => 'courses[]',--}}
    {{--	 	'label' => trans("Courses"),--}}
    {{--	 	'cols' => 'col-lg-6 col-12  courses-input',--}}
    {{--	 	'form_options'=> [--}}
    {{--	 	     'multiple',--}}

    {{--        ],--}}
    {{--	 	'select_options' =>  $courses_list,--}}
    {{--        'value' => isset($row) ? json_decode($row->access_id, true) : []--}}

    {{--	])--}}




    @include('admin.components.inputs.select', ['name' => 'deadline_type', 'label' => trans("enterprise::lang.deadline_type"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Relative", "0" => "Static" ]])

    @include('admin.components.inputs.date', [
                                        'name'        => 'expiration_date',
                                        'label'       => trans('Expires at'),
                                        'cols' => 'col-lg-6  expiration_date ',
                                        'form_options'=> [''],
                                        'value' => isset($row) ?  Carbon\Carbon::parse($row->expiration_date)->format('Y-m-d') : null

                                     ])

    @include('admin.components.inputs.number', [
    	'name' => 'expiration_days',
	 	'label' => trans("Durations in Days"),
	 	'cols' => 'col-lg-6  expiration_days',
	 	'form_options'=> [

        ],
	])
    @include('admin.components.inputs.select', [
    	'name' => 'access_type',
	 	'label' => trans("Access Type"),
	 	'form_options'=> [
	 	    'required', 'placeholder' => 'Select Access Type',
	 	    (isset($row) ? 'disabled' : ''),
	 	    'id' => 'access_type_select'
        ],
	 	'select_options' =>  [
	 	    \App\Domains\Payments\Enum\AccessType::LEARN_PATH_CAREER => '(Learn path)  Career',
	 	    \App\Domains\Payments\Enum\AccessType::LEARN_PATH_SKILL => '(Learn path)  Skill',
	 	    \App\Domains\Payments\Enum\AccessType::LEARN_PATH_CERTIFICATE => '(Learn path)  Certificate',
		]
	])
    @include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("bundles::lang.status"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Active", "0" => "Suspended" ]])
    @if(!isset($row))
        <div class="w-100">
            <div id="create-courses" class="col-12">
                <h2>Add Courses</h2>
                <div class="row create-quiz-item">
                    <div class="col-11">
                        {{--                    @include('admin.components.inputs.text', [--}}
                        {{--                        'name' => 'courses[]',--}}
                        {{--                        'label' => trans("course"),--}}
                        {{--                        'form_options'=> ['required', 'id' => 'course_name'],--}}
                        {{--                        'cols' => 'col-12',--}}
                        {{--                    ])--}}

                        @include('admin.components.inputs.select', [
        'name' => 'courses[]',
         'label' => trans("Courses"),
         'cols' => ' col-12  courses-input',
         'form_options'=> [

        ],
         'select_options' =>  $courses_list,
        'value' => isset($row) ? json_decode($row->access_id, true) : []

    ])
                        <div class="d-flex flex-row">
                            @include('admin.components.inputs.number', [
                         'name' => 'weights[]',
                         'label' => trans("Weight"),
                         'form_options'=> ['required'],
                         'cols' => 'col-lg-6 col-12',
                     ])

                            @include('admin.components.inputs.number', [
                                'name' => 'sorts[]',
                                'label' => trans("sort"),
                                'form_options'=> ['required'],
                                'cols' => 'col-lg-6 col-12',
                            ])

                    </div>
                </div>
                    <div class="col-1 mt-3">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-toggle="duplicate-input"
                            data-duplicate="#create-courses > .row"
                            data-target="#course-multiple-create"
                            data-remove=".create-quiz-item"
                            data-toggledata="<i class='fa fa-minus'></i>"
                            data-toggleclass="btn-secondary btn-danger">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div id="course-multiple-create" class="col-12">
            </div>
        </div>
    @endif

    {{--                @dd(json_decode($row->access_id, true))--}}
{{--@dd($row->courses()->get()[0]->id)--}}
    @if(isset($row))
        <div class="w-100">
            <div id="create-courses" class="col-12 px-0">
                <h2>Add Courses</h2>
                @foreach($row->courses()->orderBy('sort' ,'asc')->get() as $index => $course)

                    <div class="row justify-content-between create-quiz-item">
                        <div class="col-11">

                            @include('admin.components.inputs.select', [
                            'name' => 'courses[]',
                             'label' => trans("Courses"),
                             'cols' => ' col-12  courses-input',
                             'form_options'=> [
                             'data-select2-id' =>'start' . $index . 'courses[]',

                                 ],
                               'select_options' =>  $courses_list,
                               'value' => $course->id ])
                        <div class="d-flex flex-row">

                            @include('admin.components.inputs.number', [
                                'name' => 'weights[]',
                                'label' => trans("Weight"),
                                'form_options'=> ['required'],
                                'cols' => 'col-lg-6 col-12',
                                'value' => $course->pivot->weight
                            ])


                            @include('admin.components.inputs.number', [
                                'name' => 'sorts[]',
                                'label' => trans("sort"),
                                'form_options'=> ['required'],
                                'cols' => 'col-lg-6 col-12',
                                'value' => $course->pivot->sort
                            ])
                                </div>

                        </div>
                        <div class="col-1 mt-3">
                            @if($index == 0)
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-toggle="duplicate-input"
                                    data-duplicate="#create-courses > .row"
                                    data-target="#course-multiple-create"
                                    data-remove=".create-quiz-item"
                                    data-toggledata="<i class='fa fa-minus'></i>"
                                    data-toggleclass="btn-secondary btn-danger">
                                    <i class="fa fa-plus"></i>
                                </button>

                            @else
                                <button type="button" class="btn btn-danger" data-toggle="remove-input" data-duplicate="#create-courses > .row" data-target="#course-multiple-create" data-remove=".create-quiz-item" data-toggledata="<i class='fa fa-minus'></i>" data-toggleclass="btn-secondary btn-danger">
                                    <i class="fa fa-minus"></i>
                                </button>
                                @endif
                        </div>
                    </div>
            @endforeach
            </div>

            <div id="course-multiple-create" class="col-12">
            </div>

        </div>
    @endif

</div>


@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])





@push('script')

    <script>
        $("select").select2();

        $("select").on("select2:select", function (evt) {
            var element = evt.params.data.element;
            var $element = $(element);

            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });
        const static = "{{ \App\Domains\Enterprise\Enum\LearnPathsDeadlineType::STATIC }}";
        const relative = "{{ \App\Domains\Enterprise\Enum\LearnPathsDeadlineType::RELATIVE  }}";

        function setupFormInputs() {
            let val = $('#deadline_type').val();
            if (val == static) {
                $('.expiration_days').hide();
                $('.expiration_date').show();


            } else if (val == relative) {
                $('.expiration_days').show();
                $('.expiration_date').hide();
            }

            @if(!isset($row))
            $('[name="duration"]').val(0);
            // $('[name="categories[]"]').select2().select2('val', '0');
            // $('[name="courses[]"]').select2().select2('val', '0');
            @endif



        }

        $('[name="deadline_type"]').on('change', setupFormInputs);

        setupFormInputs();
    </script>
@endpush
