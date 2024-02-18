<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h2 class="card-header">
				Resources
			</h2>
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-12">
						@include('course::admin.lesson.resource.create', [
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
									<th>Resource</th>
									<th>@lang('lang.Actions')</th>
								</tr>
								</thead>
								<tbody>
								@foreach($lesson->resources as $resource)
									<tr>
										<td>
                                            @if($resource->attachment)
											    <a href="{{ url($resource->attachment->full_url) }}" download="{{ $resource->name }}">{{ $resource->name }}</a>
                                            @else
                                                <a href="{{ url($resource->link) }}" target="_blank">{{ $resource->name }}</a>
                                            @endif
										</td>
										<td>
											{!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'resource', $resource->id], 'class' => 'form-horizontal']) !!}
											{!! Form::hidden('id', $resource->id) !!}
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
