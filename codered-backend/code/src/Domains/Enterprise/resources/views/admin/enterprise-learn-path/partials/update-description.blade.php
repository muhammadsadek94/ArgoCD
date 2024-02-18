@isset($row)
	<h2>Description & Feature</h2>
	@foreach($row->description as $description)
		<div class="row description-feature-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'description[]',
					'label' => trans("Description"),
					'value' => $description,
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
							data-duplicate=".description-feature-item"
							data-target="#description-feature-multiple"
							data-remove=".description-feature-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#description-feature > .row"
							data-target="#description-feature-multiple"
							data-remove=".description-feature-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset