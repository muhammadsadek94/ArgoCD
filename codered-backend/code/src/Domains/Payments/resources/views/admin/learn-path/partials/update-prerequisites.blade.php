@isset($row)
	<h2>prerequisite</h2>
	@foreach($row->prerequisite ?? [] as $prerequisite)
		<div class="row prerequisite-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'prerequisite[]',
					'label' => trans("prerequisite"),
					'value' => $prerequisite ?? '',
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
							data-duplicate=".prerequisite-item"
							data-target="#prerequisite-multiple-create"
							data-remove=".prerequisite-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#prerequisite > .row"
							data-target="#prerequisite-multiple-create"
							data-remove=".prerequisite-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
<div id="prerequisite-multiple-create" class="col-12 px-0">
</div>
