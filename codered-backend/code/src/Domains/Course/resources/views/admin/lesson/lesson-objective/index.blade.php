<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h2 class="card-header">
                Objectives
			</h2>
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-12">
						@include('course::admin.lesson.lesson-objective.create', [
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
									<th>Sort</th>
									<th>@lang('lang.Actions')</th>
								</tr>
								</thead>
								<tbody>
								@foreach($lesson->lesson_objectives as $objective)
									<tr>
										<td>
											<a>{{ $objective->objective_text }}</a>
										</td>
                                        <td>
                                            <a>{{ $objective->sort }}</a>
                                        </td>
										<td>
											{!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'lesson-objective', $objective->id], 'class' => 'form-horizontal']) !!}
											{!! Form::hidden('id', $objective->id) !!}
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
