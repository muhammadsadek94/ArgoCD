@isset($row)
	<h2>jobs</h2>
{{--    @include('admin.components.inputs.text', ['name' => 'jobs_description', 'label' => trans('payments::lang.jobs_description'), 'form_options'=> ['required']])--}}

    @foreach($row->jobs ?? [] as $jobs)
		<div class="row jobs-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'jobs[]',
					'label' => trans("Title"),
					'value' =>$jobs,
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
			</div>
			<div class="col-1 mt-3">
				@if($loop->first)
					<button
							type="button"
							class="btn btn-secondary"
							data-toggle="duplicate-input"
							data-duplicate=".jobs-item"
							data-target="#jobs-multiple-create"
							data-remove=".jobs-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#what-jobs > .row"
							data-target="#jobs-multiple-create"
							data-remove=".jobs-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
<div id="jobs-multiple-create" class="col-12 px-0">
</div>
