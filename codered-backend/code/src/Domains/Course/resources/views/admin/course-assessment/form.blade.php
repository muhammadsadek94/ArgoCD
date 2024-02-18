<div class="panel-body row">


	@include('admin.components.inputs.text', [
        'name' => 'question',
        'label' => trans('Question'),
        'form_options'=> [
          'required'
        ],
        'cols' => 'col-12'
    ])

    @include('admin.components.inputs.select', [
        'name' => 'related_lesson_id',
        'label' => 'Lesson',
        'form_options'=> ['required', 'placeholder'	=> 'Select Lesson'],
        'select_options' =>  $lessons,
        'cols' => 'col-12'
    ])



    <div id="create-answers" class="col-12">
        <h2>Answers</h2>
        <div class="row create-quiz-item">
            <div class="col-11">
                @include('admin.components.inputs.text', [
                    'name' => 'answers[]',
                    'label' => trans("Answer"),
                    'form_options'=> ['required', 'id' => 'faq_answer'],
                    'cols' => 'col-12',
                ])

                @include('admin.components.inputs.radio', [
                    'name' => 'is_correct',
                    'label' => trans("is Correct"),
                    'value' => 0,
                    'form_options'=> ['required'],
                    'cols' => 'col-12',
                ])

            </div>
            <div class="col-1 mt-3">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-toggle="duplicate-input"
                    data-duplicate="#create-answers > .row"
                    data-target="#quiz-multiple-create"
                    data-remove=".create-quiz-item"
                    data-toggledata="<i class='fa fa-minus'></i>"
                    data-toggleclass="btn-secondary btn-danger">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
    <div id="quiz-multiple-create" class="col-12">
    </div>


	{!! Form::hidden('course_id', request('course_id')) !!}


	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>



