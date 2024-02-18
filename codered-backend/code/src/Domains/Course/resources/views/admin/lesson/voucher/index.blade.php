<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h2 class="card-header">
				Vouchers
			</h2>
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-12">
						@include('course::admin.lesson.voucher.create', [
    						'lesson' => $row
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
                                    <th>Used</th>
{{--									<th>@lang('lang.Actions')</th>--}}
								</tr>
								</thead>
								<tbody>
								@foreach($vouchers as $voucher)
									<tr>
										<td>
                                          <p>{{$voucher->voucher}}</p>
										</td>
                                        <td>
                                          @if($voucher->user)
                                                <a href="{{ url(Constants::ADMIN_BASE_URL . "/user/{$voucher->user_id}") }}">
                                                    {{ $voucher->user->first_name }} - {{ $voucher->user->email }}
                                                </a>
                                          @else
                                                <spane class="badge badge-danger">Not In use</spane>
                                          @endif
										</td>

{{--										<td>--}}
{{--											{!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'resource', $voucher->id], 'class' => 'form-horizontal']) !!}--}}
{{--											{!! Form::hidden('id', $voucher->id) !!}--}}
{{--											<button type="submit" class="btn btn-danger "--}}
{{--													onclick="return confirm('Confirm Delete operation ?');">--}}
{{--												<i class="fa fa-trash"></i> @lang('lang.delete')--}}
{{--											</button>--}}

{{--											{!! Form::close() !!}--}}

{{--										</td>--}}
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
