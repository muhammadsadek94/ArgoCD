<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h2 class="card-header">
                Exercises
			</h2>
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-12">
						@include('course::admin.lesson.lesson-task.create', [
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
									<th>Text</th>
									<th>Description</th>
									<th>Sort</th>
									<th>@lang('lang.Actions')</th>
								</tr>
								</thead>
								<tbody>
								@foreach($lesson->lesson_tasks as $task)
									<tr>
										<td>
											<a>{{ $task->title }}</a>
										</td>
                                        <td>
                                            <a>{{ \Illuminate\Support\Str::limit($task->description, 150) }}</a>
                                        </td>
                                        <td>
                                            <a>{{ $task->sort }}</a>
                                        </td>
										<td>
											{!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'lesson-task', $task->id], 'class' => 'form-horizontal']) !!}
											{!! Form::hidden('id', $task->id) !!}
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
