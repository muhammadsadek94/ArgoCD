@isset($row->prerequisites)
	<h4>Course Prerequisites</h4>
	@foreach($row->prerequisites as $prerequisite)
		<div class="row prerequisites">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'prerequisites[]',
					'label' => 'Prerequisite',
					'value' => $prerequisite,
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
							data-duplicate=".prerequisites"
							data-target="#prerequisites-multiple"
							data-remove=".prerequisites"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#prerequisites > .row"
							data-target="#prerequisites-multiple"
							data-remove=".prerequisites"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@else
	<h4>Course Prerequisites</h4>
	<div class="row prerequisites">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'prerequisites[]',
				'label' => 'Prerequisite',
				'value' => null,
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate=".prerequisites"
					data-target="#prerequisites-multiple"
					data-remove=".prerequisites"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-danger btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
@endisset
