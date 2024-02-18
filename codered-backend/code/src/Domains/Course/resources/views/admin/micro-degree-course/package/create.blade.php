<!-- Button trigger modal -->
<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createLessonQuiz">
    Create
</button>

<!-- Modal -->
<div class="modal fade" id="createLessonQuiz" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Package</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['method' => 'POST', 'url' => "{$route}/actions/package", 'files' => true, 'data-toggle' => 'ajax', 'data-refresh-page' => 'true', 'reset' => 'true']) !!}

            <div class="modal-body">
                <div class="container-fluid">
                    @include('admin.components.inputs.text', [
                        'name' => 'name',
                        'label' => trans('Name'),
                        'form_options' => ['required'],
                        'cols' => 'col-12',
                    ])
                    @include('admin.components.inputs.url', [
                        'name' => 'url',
                        'label' => trans('Samcart Url'),
                        'form_options' => ['required'],
                        'cols' => 'col-12 ',
                    ])
                    @include('admin.components.inputs.number', [
                        'name' => 'amount',
                        'label' => trans('Amount'),
                        'form_options' => ['required'],
                        'cols' => 'col-12',
                    ])

                    @include('admin.components.inputs.select', [
                        'name' => 'type',
                        'label' => trans('Type of Payment'),
                        'form_options' => ['required'],
                        'select_options' => [
                            \App\Domains\Course\Enum\CoursePackageType::ONE_TIME => 'One Payment',
                            \App\Domains\Course\Enum\CoursePackageType::INSTALLMENT => '3 months Installment',
                        ],
                        'cols' => 'col-12',
                    ])



                    <div class="col-12"></div>
                    <div id="create-feature" class="col-12">
                        <h2>Features</h2>
                        <ul>
                            <li>1: about courses includes (media player)</li>
                            <li>2: about assigment questions OR files includes (file)</li>
                            <li>3: lab exercies (flag)</li>
                            <li>4: cerifications (certificate)</li>
                            <li>5: subtitles includes (global) </li>
                        </ul>
                        <div class="row create-feature-item">
                            <div class="col-11">
                                @include('admin.components.inputs.text', [
                                    'name' => 'features[]',
                                    'label' => trans('Feature'),
                                    'form_options' => ['required', 'id' => 'package_feature'],
                                    'cols' => 'col-12',
                                ])
                            </div>
                            <div class="col-1 mt-3">
                                <button type="button" class="btn btn-secondary inc-feature"
                                    data-toggle="duplicate-input" data-duplicate="#create-feature > .row"
                                    data-target="#feature-multiple-create" data-remove=".create-feature-item"
                                    data-toggledata="<i class='fa fa-minus'></i>"
                                    data-toggleclass="btn-secondary btn-danger dec-feature">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="feature-multiple-create" class="col-12">
                    </div>
                    {!! Form::hidden('course_id', $course->id) !!}

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>

@push('script-bottom')
    <script>
        $('.inc-feature').on('click', function() {
            const total = getCountFeatures();

            if (total > 4) {
                $(this).attr('disabled', true);
            }
        });

        $(document).on('.dec-feature', 'click', function() {
            const total = getCountFeatures();
            console.log('hi', total)
            if (total < 5) {
                $('.inc-feature').attr('disabled', false);
            }
        });

        function getCountFeatures() {
            const features = $('.create-feature-item');
            if (features > 4) {
                $(this).attr('disabled', true);
            }
            return features.length;
        }
    </script>
@endpush
