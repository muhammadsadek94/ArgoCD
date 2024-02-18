<div class="row" id="package-form">
	<div class="col-lg-12">
		<div class="card">
			<h2 class="card-header">
				Package
			</h2>
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-12">
						@include("{$view_path}.package.create", [
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
									<th>Package Name</th>
									<th>Amount</th>
									<th>@lang('lang.Actions')</th>
								</tr>
								</thead>
								<tbody>
								@foreach($course->packages as $package)
									<tr>
										<td>{{ $package->name }}</td>
										<td>{{ $package->amount }}</td>
										<td>
											{!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'package', $package->id], 'class' => 'form-horizontal']) !!}
											{!! Form::hidden('id', $package->id) !!}
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
