@isset($row)
    {{--                @dd(json_decode($row->access_id, true))--}}
    {{--@dd($row->courses()->get()[0]->id)--}}
    @if(isset($row))
        <div class="w-100">
            <div id="create-courses" class="col-12 px-0">
                <h2>Add Courses</h2>
                @if( $row->courses() )
                @foreach($row->courses()->orderBy('sort' ,'asc')->get() as $index => $course)

                    <div class="row justify-content-between create-quiz-item">
                        <div class="col-11">

                            @include('admin.components.inputs.select', [
                            'name' => 'courses_list[]',
                             'label' => trans("Courses"),
                             'cols' => ' col-12  ',
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
                @endif
            </div>

            <div id="course-multiple-create" class="col-12">
            </div>

        </div>
        @endif

        </div>

    @endisset
