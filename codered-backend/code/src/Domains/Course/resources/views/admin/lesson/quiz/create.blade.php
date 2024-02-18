<!-- Button trigger modal -->
<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createLessonQuiz">
	Create
</button>

<!-- Modal -->
<div class="modal fade" id="createLessonQuiz" role="dialog" aria-labelledby="modelTitleId"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Create Quiz</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{!! Form::open(['method'=>'POST','url' => "{$route}/actions/quiz", 'files'=>true,'data-toggle'=> 'ajax', 'data-refresh-page' => "true", 'reset'=>"true"]) !!}

			<div class="modal-body">
				<div class="container-fluid">
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

					@include('admin.components.inputs.textarea', [
						'name' => 'description',
						'label' => trans('description'),
						'form_options'=> [
						  'required',
						  'rows'=> 2
						],
						'cols' => 'col-12'
					])


					<div class="col-12"></div>
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
					{!! Form::hidden('lesson_id', $lesson->id) !!}

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
			{!! Form::close() !!}

		</div>
	</div>
</div>

@push('script-bottom')

@endpush
