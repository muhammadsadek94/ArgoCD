<div id="courses" class="col-12">
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
        'name' => 'courses_list[]',
         'label' => trans("Courses"),
         'cols' => ' col-12',
         'form_options'=> [

        ],
         'select_options' =>  $courses_list,
        'value' => isset($row) ? json_decode($row->access_id, true) : []

    ])
                        <div class="d-flex flex-row">
                            @include('admin.components.inputs.number', [
                         'name' => 'weights[]',
                         'label' => trans("Weight"),
                         'form_options'=> [''],
                         'cols' => 'col-lg-6 col-12',
                     ])

                            @include('admin.components.inputs.number', [
                                'name' => 'sorts[]',
                                'label' => trans("sort"),
                                'form_options'=> [''],
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

        </div>
    @endif


</div>

</div>
<div id="course-multiple-create" class="col-12">
</div>
