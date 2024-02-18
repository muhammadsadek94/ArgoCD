<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h2 class="card-header">
				What To Learn
			</h2>
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-12">
						@include("{$view_path}.what-to-learn.create", [
    						'course' => $course
						])
					</div><!-- end col-->
				</div> <!-- end row-->
				
				<div class="row mb-2">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-centered table-hover table-striped mb-0">
								<thead class="thead-light">
								<tr>
									<th>Title</th>
									<th>Description</th>
									<th>Image</th>
									<th>@lang('lang.Actions')</th>
								</tr>
								</thead>
								<tbody>
								@foreach($course->course_learn()->get() as $row)
									<tr>
										<td>{{ $row->title }}</td>
										<td>{{ $row->description }}</td>
										<td><img class="img-fluid" width="100" height="100" src="{{ isset($row->image) ? $row->image->full_url : null }}"></td>
										<td>
											{!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'what-to-learn', $row->id], 'class' => 'form-horizontal']) !!}
											{!! Form::hidden('id', $row->id) !!}
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