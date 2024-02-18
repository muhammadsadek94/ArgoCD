<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h2 class="card-header">
				Quiz
			</h2>
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-12">
						@include('course::admin.lesson.quiz.create', [
    						'lesson' => $lesson
						])
					</div><!-- end col-->
				</div> <!-- end row-->

				<div class="row mb-2">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-centered table-hover table-striped mb-0">
								<thead class="thead-light">
								<tr>
									<th>Question</th>
									<th>Description</th>
									<th>Answer</th>
									<th>Related Lesson</th>
									<th>@lang('lang.Actions')</th>
								</tr>
								</thead>
								<tbody>
								@foreach($row->mcq as $quiz)
									<tr>
										<td>{{ $quiz->question }}</td>
										<td>{{ $quiz->description }}</td>
										<td>
											@if($quiz->answers_array && is_array($quiz->answers_array))
												<ul>
													@foreach($quiz->answers_array as $answer)
													<li style="@if($answer['is_correct'] == 1) color:green; @endif">
														{{ $answer['text'] ?? 'Answer not available' }}
													</li>
													@endforeach
												</ul>
											@endif
										</td>
										@if($quiz->relatedLesson)
										<td>
											<a class="text-blue" target="_blank" href="{{ url("/admin/lesson/{$quiz->relatedLesson->id}/edit?course_id={$quiz->relatedLesson->course_id}&chapter_id={$quiz->relatedLesson->chapter_id}") }}">
												{{ $quiz->relatedLesson->name }}
											</a>
										</td>
										@endif
										<td>
											{!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'quiz', $quiz->id], 'class' => 'form-horizontal']) !!}
											{!! Form::hidden('id', $quiz->id) !!}
											<button type="submit" class="btn btn-danger "
													onclick="return confirm('Confirm Delete operation ?');">
												<i class="fa fa-trash"></i> @lang('lang.delete')
											</button>

											{!! Form::close() !!}

										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div> <!-- end card-body-->
		</div> <!-- end card-->
	</div> <!-- end col-->
</div>
