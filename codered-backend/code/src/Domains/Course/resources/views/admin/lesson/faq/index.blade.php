<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h2 class="card-header">
				FAQ
			</h2>
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-12">
						@include('course::admin.lesson.faq.create', [
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
									<th>Answer</th>
									<th>@lang('lang.Actions')</th>
								</tr>
								</thead>
								<tbody>
								@foreach($row->faq as $faq)
									<tr>
										<td>{{ $faq->question }}</td>
										<td>{{ $faq->answer }}</td>
										<td>
											@include('course::admin.lesson.faq.edit', [
												'lesson' => $lesson,
												'faq' => $faq
											])
											{!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'faq', $faq->id], 'class' => 'form-horizontal']) !!}
											{!! Form::hidden('id', $faq->id) !!}
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