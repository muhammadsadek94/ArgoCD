<div id="jobs" class="col-12">
	<h2>jobs</h2>
{{--    @include('admin.components.inputs.text', ['name' => 'jobs_description', 'label' => trans('payments::lang.jobs_description'), 'form_options'=> ['required']])--}}

    <div class="row jobs-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'jobs[]',
				'label' => trans("jobs"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#jobs > .row"
					data-target="#jobs-multiple-create"
					data-remove=".jobs-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>

<div id="jobs-multiple-create" class="col-12 px-0">
</div>
