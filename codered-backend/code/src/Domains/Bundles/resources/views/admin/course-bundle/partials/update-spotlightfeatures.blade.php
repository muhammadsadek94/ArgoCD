@isset($row)
	<h4>Bundle Spotlight Features</h4>
	@foreach($row->bundle_spotlight as $feature)
		<div class="row spot-feature-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'bundle_spotlight[]',
					'label' => trans("Feature"),
					'value' => $feature,
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
				
			</div>
			<div class="col-1 mt-3">
				@if($loop->first)
					<button
							type="button"
							class="btn btn-secondary"
							data-toggle="duplicate-input-spot"
							data-spotduplicate=".spot-feature-item"
							data-spottarget="#spot-feature-multiple"
							data-spotremove=".spot-feature-item"
							data-spottoggledata="<i class='fa fa-minus'></i>"
							data-spottoggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-spotduplicate="#spot-feature > .row"
							data-spottarget="#spot-feature-multiple"
							data-spotremove=".spot-feature-item"
							data-spottoggledata="<i class='fa fa-minus'></i>"
							data-spottoggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset