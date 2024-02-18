@isset($row)
	<h2>features</h2>
	@foreach($row->features ?? [] as $features)
		<div class="row features-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'features[]',
					'label' => trans("features"),
					'value' => $features ?? '',
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
							data-duplicate=".features-item"
							data-target="#features-multiple-create"
							data-remove=".features-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#features > .row"
							data-target="#features-multiple-create"
							data-remove=".features-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
<div id="features-multiple-create" class="col-12 px-0">
</div>
